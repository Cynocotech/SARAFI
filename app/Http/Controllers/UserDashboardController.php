<?php

namespace App\Http\Controllers;

use App\Models\ExchangeOffice;
use App\Models\ExchangeRate;
use App\Models\ExchangeRateHistory;
use App\Models\OnboardingField;
use App\Services\StripeIdentityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        $office = Auth::guard('exchange')->user();
        $screens = $office instanceof ExchangeOffice && $office->canUseDigitalSignage()
            ? $office->digitalSignageScreens()->latest()->get()
            : collect();

        $subscriptionActive = $office instanceof ExchangeOffice && $office->isSubscriptionActive();
        $subscriptionDaysRemaining = $office instanceof ExchangeOffice ? $office->getSubscriptionDaysRemaining() : null;
        $currentPlan = $office instanceof ExchangeOffice ? $office->getCurrentPlan() : null;
        $planName = $currentPlan ? ($currentPlan->name_fa ?: $currentPlan->name) : null;

        return view('dashboard.index', [
            'office' => $office,
            'screens' => $screens,
            'subscriptionActive' => $subscriptionActive,
            'subscriptionDaysRemaining' => $subscriptionDaysRemaining,
            'planName' => $planName,
        ]);
    }

    public function onboarding(): View|RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if ($office instanceof ExchangeOffice && $this->isVerifiedAndActive($office)) {
            return redirect()->route('dashboard.index')->with('info', 'شما قبلاً یک صرافی تأیید‌شده دارید.');
        }

        return view('dashboard.onboarding.step1', [
            'onboardingFields' => OnboardingField::ordered()->get(),
        ]);
    }

    public function onboardingStep1(Request $request)
    {
        $office = Auth::guard('exchange')->user();
        if ($office instanceof ExchangeOffice && $this->isVerifiedAndActive($office)) {
            return redirect()->route('dashboard.index')->with('info', 'شما قبلاً یک صرافی تأیید‌شده دارید. امکان ثبت صرافی جدید وجود ندارد.');
        }

        $fields = OnboardingField::ordered()->get();
        if ($fields->isEmpty()) {
            return redirect()->route('dashboard.onboarding')->with('info', 'در حال حاضر فرم ثبت صرافی غیرفعال است. لطفاً با مدیر تماس بگیرید.');
        }

        $rules = [];
        foreach ($fields as $field) {
            $max = match ($field->key) {
                'fca_number', 'company_house_id' => 50,
                'city' => 100,
                'postcode' => 20,
                'phone' => 30,
                default => $field->type === 'textarea' ? 2000 : 255,
            };
            $rule = ['nullable', 'string', 'max:' . $max];
            if ($field->type === 'email') {
                $rule = ['nullable', 'email', 'max:255'];
            }
            if ($field->type === 'tel') {
                $rule = ['nullable', 'string', 'max:30'];
            }
            if ($field->key === 'postcode') {
                $rule[] = 'regex:' . ExchangeOffice::UK_POSTCODE_REGEX;
            }
            if ($field->required) {
                array_unshift($rule, 'required');
            }
            $rules[$field->key] = $rule;
        }

        $validated = Validator::make($request->all(), $rules)->validate();

        $allowed = array_flip(OnboardingField::ALLOWED_KEYS);
        $data = array_intersect_key($validated, $allowed);

        $office = ExchangeOffice::create([
            ...$data,
            'user_id' => $request->user()?->id,
            'status' => ExchangeOffice::STATUS_PENDING_KYC,
        ]);

        return redirect()->route('dashboard.onboarding.success', ['office' => $office->id]);
    }

    public function onboardingKyc(Request $request)
    {
        $officeId = $request->query('office');
        $office = ExchangeOffice::findOrFail($officeId);

        $returnUrl = route('dashboard.onboarding.success', ['office' => $office->id]);

        if (empty(\App\Models\Setting::get('stripe_secret')) && empty(config('services.stripe.secret'))) {
            return redirect($returnUrl)->with('info', 'احراز هویت (KYC) پس از تنظیم Stripe فعال خواهد شد.');
        }

        $stripeIdentity = app(StripeIdentityService::class);
        $session = $stripeIdentity->createVerificationSession($office, $returnUrl);

        return redirect($session->url);
    }

    public function onboardingSuccess(Request $request): View
    {
        $officeId = $request->query('office');
        $office = ExchangeOffice::findOrFail($officeId);

        return view('dashboard.onboarding.success', ['office' => $office]);
    }

    public function showSetLogin(Request $request): View|RedirectResponse
    {
        $officeId = $request->query('office');
        $office = ExchangeOffice::findOrFail($officeId);

        if ($office->username) {
            return redirect()->route('exchange.login')->with('info', 'ورود برای این صرافی از قبل تنظیم شده است.');
        }

        return view('dashboard.set-login', ['office' => $office]);
    }

    public function setLogin(Request $request): RedirectResponse
    {
        $officeId = $request->query('office') ?? $request->input('office_id');
        $office = ExchangeOffice::findOrFail($officeId);

        if ($office->username) {
            return redirect()->route('exchange.login')->with('info', 'ورود برای این صرافی از قبل تنظیم شده است.');
        }

        $validated = Validator::make($request->all(), [
            'office_id' => ['required', 'in:' . $office->id],
            'username' => ['required', 'string', 'max:255', 'unique:exchange_offices,username,' . $office->id],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.required' => 'نام کاربری را وارد کنید.',
            'username.unique' => 'این نام کاربری قبلاً استفاده شده است.',
            'password.min' => 'رمز عبور حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
        ])->validate();

        $office->update([
            'username' => $validated['username'],
            'password' => $validated['password'],
        ]);

        return redirect()->route('exchange.login')->with('success', 'نام کاربری و رمز عبور ثبت شد. اکنون می‌توانید وارد شوید.');
    }

    public function showPasswordForm(Request $request): View
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        return view('dashboard.password', ['office' => $office]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        $rules = [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        $messages = [
            'password.required' => 'رمز عبور جدید را وارد کنید.',
            'password.min' => 'رمز عبور حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
        ];

        if (filled($office->password)) {
            $rules['current_password'] = ['required', 'string'];
            $messages['current_password.required'] = 'رمز عبور فعلی را وارد کنید.';
        }

        $validated = Validator::make($request->all(), $rules, $messages)->validate();

        if (filled($office->password)) {
            if (! Hash::check($validated['current_password'], $office->password)) {
                return back()->withErrors(['current_password' => 'رمز عبور فعلی اشتباه است.']);
            }
        }

        $office->update(['password' => $validated['password']]);

        return redirect()->route('dashboard.password')->with('success', 'رمز عبور با موفقیت تغییر کرد.');
    }

    protected function isVerifiedAndActive(ExchangeOffice $office): bool
    {
        return $office->identity_verified || $office->status === ExchangeOffice::STATUS_ACTIVE;
    }

    /**
     * Offices that the logged-in exchange can manage (only their own, if verified).
     */
    protected function verifiedOfficesForUser(Request $request)
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            return collect();
        }

        if (! $office->identity_verified && $office->status !== ExchangeOffice::STATUS_ACTIVE) {
            return collect();
        }

        return collect([$office->load('exchangeRates')]);
    }

    /**
     * Ensure the office belongs to the logged-in exchange and is verified.
     */
    protected function authorizeOfficeForRates(Request $request, ExchangeOffice $office): void
    {
        $current = Auth::guard('exchange')->user();
        if (! $current || $current->id !== $office->id) {
            abort(403, 'این صرافی به شما تعلق ندارد.');
        }
        if (! $office->identity_verified && $office->status !== ExchangeOffice::STATUS_ACTIVE) {
            abort(403, 'پس از تأیید صرافی توسط ادمین می‌توانید نرخ‌ها را مدیریت کنید.');
        }
    }

    public function rates(Request $request): View|RedirectResponse
    {
        $offices = $this->verifiedOfficesForUser($request);

        if ($offices->isEmpty()) {
            return view('dashboard.rates', ['offices' => $offices]);
        }

        if ($offices->count() === 1) {
            return redirect()->route('dashboard.office-rates', $offices->first());
        }

        return view('dashboard.rates', ['offices' => $offices]);
    }

    public function officeRates(Request $request, ExchangeOffice $office): View
    {
        $this->authorizeOfficeForRates($request, $office);
        $office->load('exchangeRates');

        return view('dashboard.office-rates', ['office' => $office]);
    }

    public function storeRate(Request $request, ExchangeOffice $office): RedirectResponse
    {
        $this->authorizeOfficeForRates($request, $office);

        $validated = Validator::make($request->all(), [
            'from_currency' => ['required', 'string', 'size:3'],
            'to_currency' => ['required', 'string', 'size:3'],
            'buy_rate' => ['required', 'numeric', 'min:0'],
            'sell_rate' => ['required', 'numeric', 'min:0'],
            'margin' => ['nullable', 'numeric', 'min:0'],
        ])->validate();

        $exists = $office->exchangeRates()
            ->where('from_currency', $validated['from_currency'])
            ->where('to_currency', $validated['to_currency'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['from_currency' => 'این جفت ارز قبلاً ثبت شده است. برای تغییر، نرخ موجود را ویرایش کنید.']);
        }

        $office->exchangeRates()->create($validated);

        ExchangeRateHistory::create([
            'exchange_office_id' => $office->id,
            'from_currency' => $validated['from_currency'],
            'to_currency' => $validated['to_currency'],
            'buy_rate' => $validated['buy_rate'],
            'sell_rate' => $validated['sell_rate'],
            'recorded_at' => now(),
        ]);

        return redirect()->route('dashboard.office-rates', $office)->with('success', 'نرخ با موفقیت اضافه شد.');
    }

    public function updateRate(Request $request, ExchangeRate $rate): RedirectResponse
    {
        $office = $rate->exchangeOffice;
        $this->authorizeOfficeForRates($request, $office);

        $validated = Validator::make($request->all(), [
            'buy_rate' => ['required', 'numeric', 'min:0'],
            'sell_rate' => ['required', 'numeric', 'min:0'],
            'margin' => ['nullable', 'numeric', 'min:0'],
        ])->validate();

        $rate->update($validated);

        ExchangeRateHistory::create([
            'exchange_office_id' => $office->id,
            'from_currency' => $rate->from_currency,
            'to_currency' => $rate->to_currency,
            'buy_rate' => $validated['buy_rate'],
            'sell_rate' => $validated['sell_rate'],
            'recorded_at' => now(),
        ]);

        return redirect()->route('dashboard.office-rates', $office)->with('success', 'نرخ به‌روزرسانی شد.');
    }

    public function deleteRate(Request $request, ExchangeRate $rate): RedirectResponse
    {
        $office = $rate->exchangeOffice;
        $this->authorizeOfficeForRates($request, $office);
        $rate->delete();

        return redirect()->route('dashboard.office-rates', $office)->with('success', 'نرخ حذف شد.');
    }

    public function updateSpecialRate(Request $request, ExchangeOffice $office): RedirectResponse
    {
        $this->authorizeOfficeForRates($request, $office);

        if ($request->boolean('clear')) {
            $office->update(['special_rate_buy' => null, 'special_rate_sell' => null]);

            return redirect()->route('dashboard.office-rates', $office)->with('success', 'نرخ ویژه امروز حذف شد.');
        }

        $validated = Validator::make($request->all(), [
            'special_rate_option' => ['nullable', 'string', 'in:buy,sell,both'],
            'special_rate_buy' => ['nullable', 'numeric', 'min:0'],
            'special_rate_sell' => ['nullable', 'numeric', 'min:0'],
        ], [
            'special_rate_buy.numeric' => 'نرخ خرید ویژه باید عدد باشد.',
            'special_rate_sell.numeric' => 'نرخ فروش ویژه باید عدد باشد.',
        ])->validate();

        $option = $validated['special_rate_option'] ?? 'both';
        $buy = isset($validated['special_rate_buy']) && $validated['special_rate_buy'] !== '' ? (float) $validated['special_rate_buy'] : null;
        $sell = isset($validated['special_rate_sell']) && $validated['special_rate_sell'] !== '' ? (float) $validated['special_rate_sell'] : null;

        if ($option === 'buy') {
            $sell = null;
        }
        if ($option === 'sell') {
            $buy = null;
        }

        $office->update([
            'special_rate_buy' => $buy,
            'special_rate_sell' => $sell,
        ]);

        return redirect()->route('dashboard.office-rates', $office)->with('success', 'نرخ ویژه امروز ذخیره شد.');
    }

    public function updatePaymentMethods(Request $request, ExchangeOffice $office): RedirectResponse
    {
        $this->authorizeOfficeForRates($request, $office);

        $validated = Validator::make($request->all(), [
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string', 'in:credit_cards,visa,mastercard,cash'],
        ], [
            'payment_methods.*.in' => 'روش پرداخت نامعتبر است.',
        ])->validate();

        $methods = $validated['payment_methods'] ?? [];
        $office->update(['payment_methods' => array_values(array_unique($methods))]);

        return redirect()->route('dashboard.office-rates', $office)->with('success', 'روش‌های پرداخت پذیرفته‌شده ذخیره شد.');
    }

    public function updateTransferFee(Request $request, ExchangeOffice $office): RedirectResponse
    {
        $this->authorizeOfficeForRates($request, $office);

        $validated = Validator::make($request->all(), [
            'transfer_fee_under_amount' => ['nullable', 'numeric', 'min:0'],
            'transfer_fee_amount' => ['nullable', 'numeric', 'min:0'],
        ], [
            'transfer_fee_under_amount.numeric' => 'مبلغ حد (پوند) باید عدد باشد.',
            'transfer_fee_amount.numeric' => 'مبلغ کارمزد (پوند) باید عدد باشد.',
        ])->validate();

        $under = isset($validated['transfer_fee_under_amount']) && $validated['transfer_fee_under_amount'] !== '' ? (float) $validated['transfer_fee_under_amount'] : null;
        $fee = isset($validated['transfer_fee_amount']) && $validated['transfer_fee_amount'] !== '' ? (float) $validated['transfer_fee_amount'] : null;
        // If only one is set, clear both so we don't show incomplete fee.
        if (($under === null) !== ($fee === null)) {
            $under = null;
            $fee = null;
        }
        $office->update([
            'transfer_fee_under_amount' => $under,
            'transfer_fee_amount' => $fee,
        ]);

        return redirect()->route('dashboard.office-rates', $office)->with('success', 'کارمزد حواله ذخیره شد.');
    }

    /**
     * Show rate history chart for the logged-in exchange (GBP/IRR, last 30 days).
     */
    public function ratesHistory(Request $request): View
    {
        $office = Auth::guard('exchange')->user();
        $chartLabels = [];
        $chartBuy = [];
        $chartSell = [];

        if ($office instanceof ExchangeOffice && ($office->identity_verified || $office->status === ExchangeOffice::STATUS_ACTIVE)) {
            $history = ExchangeRateHistory::query()
                ->where('exchange_office_id', $office->id)
                ->where('from_currency', 'GBP')
                ->where('to_currency', 'IRR')
                ->where('recorded_at', '>=', now()->subDays(30))
                ->orderBy('recorded_at')
                ->get();

            $byDay = $history->groupBy(fn ($r) => $r->recorded_at->format('Y-m-d'))
                ->map(fn ($group) => $group->sortByDesc('recorded_at')->first());

            $lastBuy = null;
            $lastSell = null;
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayKey = $date->format('Y-m-d');
                $chartLabels[] = farsi_num($date->format('d')) . '/' . farsi_num($date->format('n'));
                if (isset($byDay[$dayKey])) {
                    $lastBuy = (float) $byDay[$dayKey]->buy_rate;
                    $lastSell = (float) $byDay[$dayKey]->sell_rate;
                }
                $chartBuy[] = $lastBuy;
                $chartSell[] = $lastSell;
            }
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $chartLabels[] = farsi_num($date->format('d')) . '/' . farsi_num($date->format('n'));
                $chartBuy[] = null;
                $chartSell[] = null;
            }
        }

        $historyList = collect();
        if ($office instanceof ExchangeOffice && ($office->identity_verified || $office->status === ExchangeOffice::STATUS_ACTIVE)) {
            $historyList = ExchangeRateHistory::query()
                ->where('exchange_office_id', $office->id)
                ->where('from_currency', 'GBP')
                ->where('to_currency', 'IRR')
                ->orderByDesc('recorded_at')
                ->limit(30)
                ->get();
        }

        return view('dashboard.rates-history', [
            'chartLabels' => $chartLabels,
            'chartBuy' => $chartBuy,
            'chartSell' => $chartSell,
            'historyList' => $historyList,
        ]);
    }

    public function updateLogo(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $isAjax = $request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'دسترسی غیرمجاز.', 'errors' => ['logo' => ['دسترسی غیرمجاز.']]], 403);
            }
            abort(403);
        }

        try {
            $file = $request->file('logo');
            if ($file && ! $file->isValid()) {
                $err = $file->getError();
                $msg = $err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE
                    ? 'حجم فایل بیش از حد مجاز است. حداکثر ۲ مگابایت.'
                    : 'خطا در آپلود فایل. دوباره تلاش کنید.';
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => $msg, 'errors' => ['logo' => [$msg]]], 422);
                }
                return redirect()->route('dashboard.index')->withErrors(['logo' => $msg]);
            }

            $validator = Validator::make($request->all(), [
                'logo' => ['required', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
            ], [
                'logo.required' => 'لطفاً یک فایل تصویر انتخاب کنید.',
                'logo.image' => 'فایل باید یک تصویر معتبر باشد.',
                'logo.mimes' => 'فرمت مجاز: JPG، PNG، GIF یا WebP.',
                'logo.max' => 'حداکثر حجم فایل ۲ مگابایت است.',
            ]);

            if ($validator->fails()) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'اعتبارسنجی ناموفق.',
                        'errors' => $validator->errors(),
                    ], 422);
                }
                return redirect()->route('dashboard.index')->withErrors($validator)->withInput();
            }

            $this->ensureStorageLinkExists();

            $disk = Storage::disk('public');
            if (! $disk->exists('exchange-logos')) {
                $disk->makeDirectory('exchange-logos');
            }

            $path = $file->store('exchange-logos', 'public');
            if ($office->logo_path) {
                $disk->delete($office->logo_path);
            }
            $office->update(['logo_path' => $path, 'logo_url' => null]);

            $logoUrl = $office->fresh()->logoUrl();

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'لوگو با موفقیت به‌روزرسانی شد.',
                    'logo_url' => $logoUrl,
                ]);
            }

            return redirect()->route('dashboard.index')->with('success', 'لوگو با موفقیت به‌روزرسانی شد.');
        } catch (\Throwable $e) {
            if ($isAjax) {
                \Illuminate\Support\Facades\Log::error('Logo upload failed: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json([
                    'success' => false,
                    'message' => 'خطا در ذخیره لوگو. لطفاً بعداً تلاش کنید یا با پشتیبانی تماس بگیرید.',
                    'errors' => ['logo' => [$e->getMessage()]],
                ], 500);
            }
            throw $e;
        }
    }

    public function updateLogoUrl(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $isAjax = $request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'دسترسی غیرمجاز.'], 403);
            }
            abort(403);
        }

        $validated = Validator::make($request->all(), [
            'logo_url' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    $v = is_string($value) ? trim($value) : $value;
                    if ($v !== '' && $v !== null && ! filter_var($v, FILTER_VALIDATE_URL)) {
                        $fail(__('validation.url'));
                    }
                },
            ],
        ], [
            'logo_url.url' => 'لطفاً یک آدرس اینترنتی معتبر وارد کنید.',
        ])->validate();

        $url = isset($validated['logo_url']) ? trim((string) $validated['logo_url']) : null;
        if (filled($url) && ! filter_var($url, FILTER_VALIDATE_URL)) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'آدرس معتبر نیست.'], 422);
            }
            return redirect()->route('dashboard.index')->withErrors(['logo_url' => 'آدرس معتبر نیست.']);
        }

        if (filled($url)) {
            $oldPath = $office->logo_path;
            $office->update(['logo_url' => $url, 'logo_path' => null]);
            if (filled($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        } else {
            $office->update(['logo_url' => null]);
        }

        $logoUrl = $office->fresh()->logoUrl();

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'لینک لوگو ذخیره شد.',
                'logo_url' => $logoUrl,
            ]);
        }

        return redirect()->route('dashboard.index')->with('success', 'لینک لوگو ذخیره شد.');
    }

    private function ensureStorageLinkExists(): void
    {
        $link = public_path('storage');
        if (file_exists($link)) {
            return;
        }
        $target = storage_path('app/public');
        if (! is_dir($target)) {
            return;
        }
        if (function_exists('symlink') && ! windows_os()) {
            @symlink($target, $link);
        }
    }

    public function landing(): View|RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        return view('dashboard.landing', ['office' => $office]);
    }

    public function updateLanding(Request $request): RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        $validated = $request->validate([
            'tagline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:5000'],
            'about_image_url' => ['nullable', 'string', 'url', 'max:2048'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:4096'],
            'hero_image_url' => ['nullable', 'string', 'max:500'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'map_embed' => ['nullable', 'string', 'max:2000'],
            'whatsapp_phone' => ['nullable', 'string', 'max:30'],
            'landing_show_calculator' => ['nullable', 'boolean'],
            'landing_show_map' => ['nullable', 'boolean'],
            'landing_show_rates' => ['nullable', 'boolean'],
            'landing_show_contact' => ['nullable', 'boolean'],
            'services' => ['nullable', 'array'],
            'services.*.title' => ['nullable', 'string', 'max:255'],
            'services.*.description' => ['nullable', 'string', 'max:500'],
            'services.*.icon' => ['nullable', 'string', 'in:' . implode(',', array_keys(ExchangeOffice::serviceIconOptions()))],
        ]);

        $data = [
            'tagline' => $validated['tagline'] ?? null,
            'about' => $validated['about'] ?? null,
            'about_image_url' => (filled($validated['about_image_url'] ?? null) && filter_var($validated['about_image_url'], FILTER_VALIDATE_URL)) ? $validated['about_image_url'] : null,
            'hero_title' => $validated['hero_title'] ?? null,
            'hero_subtitle' => $validated['hero_subtitle'] ?? null,
            'hero_image_url' => (filled($validated['hero_image_url'] ?? null) && filter_var($validated['hero_image_url'], FILTER_VALIDATE_URL)) ? $validated['hero_image_url'] : null,
            'map_embed' => $validated['map_embed'] ?? null,
            'whatsapp_phone' => isset($validated['whatsapp_phone']) && trim((string) $validated['whatsapp_phone']) !== '' ? trim((string) $validated['whatsapp_phone']) : null,
            'landing_show_calculator' => $request->boolean('landing_show_calculator'),
            'landing_show_map' => $request->boolean('landing_show_map'),
            'landing_show_rates' => $request->boolean('landing_show_rates'),
            'landing_show_contact' => $request->boolean('landing_show_contact'),
            'services' => $this->normalizeServicesFromRequest($request->input('services', [])),
        ];

        if (! empty($validated['remove_hero_image'])) {
            if ($office->hero_image_path) {
                Storage::disk('public')->delete($office->hero_image_path);
            }
            $data['hero_image_path'] = null;
        } elseif ($request->hasFile('hero_image')) {
            if ($office->hero_image_path) {
                Storage::disk('public')->delete($office->hero_image_path);
            }
            $path = $request->file('hero_image')->store('landing-heroes', 'public');
            $data['hero_image_path'] = $path;
        }

        $office->update($data);

        return redirect()->route('dashboard.landing')->with('success', 'تنظیمات سایت ذخیره شد.');
    }

    /**
     * @param array<int, array{title?: string, description?: string, icon?: string}> $raw
     * @return array<int, array{title: string, description: string, icon: string}>
     */
    private function normalizeServicesFromRequest(array $raw): array
    {
        $allowedIcons = array_keys(ExchangeOffice::serviceIconOptions());
        $out = [];
        foreach ($raw as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $icon = $item['icon'] ?? 'payments';
            if (! in_array($icon, $allowedIcons, true)) {
                $icon = 'payments';
            }
            $out[] = [
                'title' => $title,
                'description' => trim((string) ($item['description'] ?? '')),
                'icon' => $icon,
            ];
        }
        return $out;
    }

    public function telegram(Request $request): View
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        return view('dashboard.telegram', [
            'office' => $office,
            'hasToken' => filled($office->getRawOriginal('telegram_bot_token')),
            'telegramChatId' => $office->telegram_chat_id,
        ]);
    }

    public function updateTelegram(Request $request): RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        $validated = $request->validate([
            'telegram_bot_token' => ['nullable', 'string', 'max:255'],
            'telegram_chat_id' => ['nullable', 'string', 'max:255'],
        ]);

        $data = ['telegram_chat_id' => $validated['telegram_chat_id'] ?: null];
        if ($request->filled('telegram_bot_token')) {
            $data['telegram_bot_token'] = $validated['telegram_bot_token'];
        }

        $office->forceFill($data)->save();

        return redirect()->route('dashboard.telegram')->with('success', 'تنظیمات تلگرام ذخیره شد.');
    }

    public function sendTelegramRates(Request $request): RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        $token = $office->getRawOriginal('telegram_bot_token');
        $chatId = $office->telegram_chat_id;

        if (! $token || ! $chatId) {
            return redirect()->route('dashboard.telegram')
                ->withErrors(['telegram' => 'ابتدا توکن ربات و شناسه کانال/چت را ذخیره کنید.']);
        }

        $rates = $office->exchangeRates()
            ->where('from_currency', 'GBP')
            ->where('to_currency', 'IRR')
            ->get();

        $lines = ["📊 نرخ‌های صرافی {$office->name}", ''];
        if ($rates->isEmpty()) {
            $lines[] = 'هنوز نرخ پوند به تومان ثبت نشده است.';
        } else {
            foreach ($rates as $r) {
                $lines[] = '🟢 خرید: ' . number_format((float) $r->buy_rate, 0) . ' تومان';
                $lines[] = '🔴 فروش: ' . number_format((float) $r->sell_rate, 0) . ' تومان';
                $lines[] = '';
            }
        }
        $lines[] = '— ' . now()->timezone('Asia/Tehran')->format('Y/m/d H:i');

        $text = implode("\n", $lines);

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $response = Http::asForm()->post($url, [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => null,
        ]);

        if (! $response->successful()) {
            $body = $response->json();
            $desc = $body['description'] ?? $response->body();
            return redirect()->route('dashboard.telegram')
                ->withErrors(['telegram' => 'ارسال به تلگرام ناموفق: ' . $desc]);
        }

        return redirect()->route('dashboard.telegram')->with('success', 'نرخ‌ها به تلگرام ارسال شد.');
    }
}
