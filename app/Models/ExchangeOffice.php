<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExchangeOffice extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_KYC = 'pending_kyc';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';

    /** UK postcode regex: 1-2 letters, digit, optional letter, space, digit, 2 letters */
    public const UK_POSTCODE_REGEX = '/^[A-Z]{1,2}[0-9R][0-9A-Z]?\s?[0-9][ABD-HJLNP-UW-Z]{2}$/i';

    protected $fillable = [
        'user_id',
        'name',
        'tagline',
        'about',
        'about_image_url',
        'hero_title',
        'hero_subtitle',
        'hero_image_path',
        'hero_image_url',
        'map_embed',
        'landing_show_calculator',
        'landing_show_map',
        'landing_show_rates',
        'landing_show_contact',
        'landing_theme',
        'primary_color',
        'services',
        'fca_number',
        'company_house_id',
        'address_line_1',
        'city',
        'postcode',
        'status',
        'stripe_verification_session_id',
        'identity_verified',
        'clicks',
        'phone',
        'email',
        'whatsapp_phone',
        'logo_path',
        'logo_url',
        'username',
        'password',
        'two_factor_secret',
        'telegram_bot_token',
        'telegram_chat_id',
        'plan_id',
        'features',
        'currencies',
        'special_rate_buy',
        'special_rate_sell',
        'payment_methods',
        'transfer_fee_under_amount',
        'transfer_fee_amount',
        'blocked_at',
        'blocked_reason',
    ];

    protected $hidden = [
        'password',
        'telegram_bot_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'identity_verified' => 'boolean',
        'password' => 'hashed',
        'features' => 'array',
        'currencies' => 'array',
        'special_rate_buy' => 'decimal:4',
        'special_rate_sell' => 'decimal:4',
        'payment_methods' => 'array',
        'transfer_fee_under_amount' => 'decimal:2',
        'transfer_fee_amount' => 'decimal:2',
        'two_factor_confirmed_at' => 'datetime',
        'blocked_at' => 'datetime',
        'landing_show_calculator' => 'boolean',
        'landing_show_map' => 'boolean',
        'landing_show_rates' => 'boolean',
        'landing_show_contact' => 'boolean',
        'services' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exchangeRates(): HasMany
    {
        return $this->hasMany(ExchangeRate::class);
    }

    public function exchangeRateHistory(): HasMany
    {
        return $this->hasMany(ExchangeRateHistory::class);
    }

    public function exchangeClicks(): HasMany
    {
        return $this->hasMany(ExchangeClick::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function digitalSignageScreens(): HasMany
    {
        return $this->hasMany(DigitalSignageScreen::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /** Current plan (assigned by admin or from latest paid transaction). */
    public function getCurrentPlan(): ?Plan
    {
        if ($this->plan_id) {
            return $this->relationLoaded('plan') ? $this->plan : $this->plan()->first();
        }
        $tx = $this->transactions()->whereNotNull('paid_at')->with('plan')->latest('paid_at')->first();
        return $tx?->plan;
    }

    /** Whether this office can use digital signage (has purchased a plan with the feature). */
    public function canUseDigitalSignage(): bool
    {
        $plan = $this->getCurrentPlan();
        return $plan && $plan->hasFeature(Plan::FEATURE_DIGITAL_SIGNAGE);
    }

    /**
     * Subscription period end date from the latest paid transaction (paid_at + plan interval).
     * Returns null if no paid transaction or plan has no interval.
     */
    public function getSubscriptionEndDate(): ?\Carbon\Carbon
    {
        $tx = $this->transactions()->whereNotNull('paid_at')->with('plan')->latest('paid_at')->first();
        if (! $tx || ! $tx->paid_at || ! $tx->plan) {
            return null;
        }
        $months = (int) $tx->plan->interval;
        if ($months <= 0) {
            $months = 1;
        }

        return $tx->paid_at->copy()->addMonths($months);
    }

    /**
     * Whether the subscription is active: has a current plan and, if from a paid transaction, period has not ended.
     */
    public function isSubscriptionActive(): bool
    {
        $plan = $this->getCurrentPlan();
        if (! $plan) {
            return false;
        }
        $end = $this->getSubscriptionEndDate();
        if ($end === null) {
            return true;
        }

        return $end->isFuture();
    }

    /** Days remaining in the current subscription period, or null if not applicable. */
    public function getSubscriptionDaysRemaining(): ?int
    {
        $end = $this->getSubscriptionEndDate();
        if (! $end || ! $end->isFuture()) {
            return null;
        }

        return (int) now()->diffInDays($end, false);
    }

    /**
     * Feature keys this exchange has access to (from assigned plan or paid plan subscriptions).
     *
     * @return array<int, string> feature keys
     */
    public function getActiveFeatureKeys(): array
    {
        $plan = $this->getCurrentPlan();
        if ($plan) {
            return array_values($plan->features ?? []);
        }
        $keys = $this->transactions()
            ->whereNotNull('paid_at')
            ->with('plan')
            ->get()
            ->pluck('plan')
            ->filter()
            ->flatMap(fn (Plan $p) => $p->features ?? [])
            ->unique()
            ->values()
            ->all();

        return array_values($keys);
    }

    /**
     * Human-readable feature labels for this exchange (from paid plans).
     *
     * @return array<int, string> Persian (or locale) labels
     */
    public function getActiveFeatureLabels(): array
    {
        $opts = Plan::featureOptions();
        $keys = $this->getActiveFeatureKeys();

        return array_values(array_filter(array_map(
            fn ($key) => $opts[$key] ?? $key,
            $keys
        )));
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function logoUrl(): ?string
    {
        if (filled($this->logo_url) && filter_var($this->logo_url, FILTER_VALIDATE_URL)) {
            return $this->logo_url;
        }
        if (! $this->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    public function heroImageUrl(): ?string
    {
        if (filled($this->hero_image_url) && filter_var($this->hero_image_url, FILTER_VALIDATE_URL)) {
            return $this->hero_image_url;
        }
        if (! $this->hero_image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->hero_image_path);
    }

    /** URL for the about section image on the landing page. Falls back to default image if not set. */
    public function aboutImageUrl(): string
    {
        $default = 'https://panel.cybercina.co.uk/storage/news/gghOr4HYXWjrH2W5kUvUs0EFVGDrrPh0jVaYZLjH.png';
        if (filled($this->about_image_url) && filter_var($this->about_image_url, FILTER_VALIDATE_URL)) {
            return $this->about_image_url;
        }

        return $default;
    }

    public function isVerified(): bool
    {
        return $this->identity_verified === true;
    }

    public static function postcodeRule(): string
    {
        return 'regex:' . self::UK_POSTCODE_REGEX;
    }

    /** Full address line from address fields (for display and map search). */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->city,
            $this->postcode ? strtoupper($this->postcode) : null,
        ]);

        return trim(implode(', ', $parts));
    }

    /** OpenStreetMap search URL using address fields. Empty string if no address. */
    public function getOpenStreetMapSearchUrl(): string
    {
        $address = $this->getFullAddress();
        if ($address === '') {
            return '';
        }

        return 'https://www.openstreetmap.org/search?query=' . rawurlencode($address);
    }

    /**
     * WhatsApp wa.me URL for floating button / contact. Returns null if whatsapp_phone not set.
     * Number is normalized to digits only (e.g. 989123456789 or 442071234567).
     */
    public function getWhatsAppUrl(?string $prefillText = null): ?string
    {
        $raw = $this->whatsapp_phone ?? '';
        $digits = preg_replace('/\D/', '', $raw);
        if ($digits === '') {
            return null;
        }
        $url = 'https://wa.me/' . $digits;
        if ($prefillText !== null && $prefillText !== '') {
            $url .= '?text=' . rawurlencode($prefillText);
        }
        return $url;
    }

    /**
     * Geocode full address via Nominatim (cached). Returns ['lat' => float, 'lon' => float] or null.
     */
    public function getMapCoordinates(): ?array
    {
        $address = $this->getFullAddress();
        if ($address === '') {
            return null;
        }

        $cacheKey = 'geocode:' . md5($address);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($address) {
            $response = Http::withHeaders([
                'User-Agent' => 'ExchangeLanding/1.0 (https://github.com/exchange-landing)',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
            ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();
            if (! is_array($data) || empty($data)) {
                return null;
            }

            $first = $data[0];
            $lat = isset($first['lat']) ? (float) $first['lat'] : null;
            $lon = isset($first['lon']) ? (float) $first['lon'] : null;

            if ($lat === null || $lon === null) {
                return null;
            }

            return ['lat' => $lat, 'lon' => $lon];
        });
    }

    /**
     * OpenStreetMap embed iframe URL for this address (when no custom map_embed). Null if no coordinates.
     */
    public function getOpenStreetMapEmbedUrl(): ?string
    {
        $coords = $this->getMapCoordinates();
        if (! $coords) {
            return null;
        }

        $lat = $coords['lat'];
        $lon = $coords['lon'];
        $delta = 0.02;
        $bbox = implode('%2C', [$lon - $delta, $lat - $delta, $lon + $delta, $lat + $delta]);

        return 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&layer=mapnik&marker=' . $lat . '%2C' . $lon;
    }

    /** Best features for a money exchange (for directory display and admin form). */
    public static function exchangeFeatureOptions(): array
    {
        return [
            'highlight' => 'هایلایت (نمایش برتر)',
            'best_rates' => 'بهترین نرخ',
            'no_commission' => 'بدون کارمزد',
            '24_7' => '۲۴ ساعته',
            'physical_branch' => 'شعبه فیزیکی',
            'fast_transfer' => 'انتقال سریع',
            'online_booking' => 'رزرو آنلاین',
            'fca_regulated' => 'دارای مجوز FCA',
            'high_limit' => 'سقف بالا',
            'multi_currency' => 'چند ارزه',
        ];
    }

    /** Supported currency codes and labels (for admin form and directory filter). */
    public static function supportedCurrencyOptions(): array
    {
        return [
            'GBP' => 'پوند (£)',
            'EUR' => 'یورو (€)',
            'USD' => 'دلار آمریکا ($)',
            'AED' => 'درهم امارات',
            'CAD' => 'دلار کانادا (C$)',
            'IRR' => 'تومان',
        ];
    }

    /** Landing page theme options (per-exchange override). */
    public static function landingThemeOptions(): array
    {
        return [
            '' => 'استفاده از تم پیش‌فرض سایت',
            'default' => 'پیش‌فرض (سارافی — نارنجی)',
            'theme2_fintech' => 'Theme 2 Fintech (نارنجی و مشکی)',
            'theme2' => 'Theme 2 (نavy و طلایی)',
        ];
    }

    /** Material Symbol icon names for landing services (Google Material Symbols). */
    public static function serviceIconOptions(): array
    {
        return [
            'send_money' => 'حواله / ارسال پول',
            'currency_exchange' => 'تبدیل ارز',
            'payments' => 'پرداخت',
            'account_balance' => 'بانک / حساب',
            'savings' => 'پس‌انداز',
            'trending_up' => 'نرخ صعودی',
            'swap_horiz' => 'مبادله',
            'business_center' => 'خدمات شرکتی',
            'support_agent' => 'پشتیبانی',
            'schedule' => '۲۴ ساعته',
            'verified_user' => 'امنیت',
            'local_atm' => 'نقد / ATM',
            'credit_card' => 'کارت اعتباری',
            'flight_takeoff' => 'انتقال بین‌المللی',
            'public' => 'جهانی',
            'handshake' => 'همکاری',
            'show_chart' => 'سرمایه‌گذاری',
            'receipt_long' => 'رسید و گزارش',
        ];
    }

    /**
     * Services for landing "خدمات ما" section. Returns exchange services or defaults.
     *
     * @return array<int, array{title: string, description: string, icon: string}>
     */
    public function getLandingServices(): array
    {
        $custom = $this->services ?? [];
        if (is_array($custom) && count($custom) > 0) {
            return array_values(array_map(function ($item) {
                return [
                    'title' => $item['title'] ?? '',
                    'description' => $item['description'] ?? '',
                    'icon' => $item['icon'] ?? 'payments',
                ];
            }, $custom));
        }

        return [
            ['title' => 'انتقال سریع وجه', 'description' => 'انتقال آنی وجوه به داخل و خارج کشور با کمترین کارمزد و بالاترین امنیت.', 'icon' => 'send_money'],
            ['title' => 'تعویض نقدی', 'description' => 'خرید و فروش ارزهای مختلف به صورت نقدی با نرخ‌های شفاف و رقابتی.', 'icon' => 'currency_exchange'],
            ['title' => 'خدمات شرکتی', 'description' => 'پشتیبانی اختصاصی برای بنگاه‌های اقتصادی و انجام معاملات عمده.', 'icon' => 'business_center'],
        ];
    }

    /** Human-readable feature labels for this office (from features column). Excludes 'highlight' (used only for featured placement/border). */
    public function getExchangeFeatureLabels(): array
    {
        $opts = self::exchangeFeatureOptions();
        $keys = $this->features ?? [];
        $keys = array_values(array_filter($keys, fn ($key) => $key !== 'highlight'));

        return array_values(array_filter(array_map(
            fn ($key) => $opts[$key] ?? $key,
            $keys
        )));
    }

    /** Whether this exchange has a special rate today (buy and/or sell). */
    public function hasSpecialRateToday(): bool
    {
        return $this->special_rate_buy !== null || $this->special_rate_sell !== null;
    }

    /** Whether this office has a transfer fee (under amount + fee amount set). */
    public function hasTransferFee(): bool
    {
        return $this->transfer_fee_under_amount !== null && $this->transfer_fee_amount !== null;
    }

    /** Accepted payment method keys (e.g. credit_cards, visa, mastercard, cash). */
    public static function paymentMethodOptions(): array
    {
        return [
            'credit_cards' => 'کارت اعتباری',
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            'cash' => 'نقد',
        ];
    }

    /** Keys of payment methods this office accepts (for display). */
    public function getAcceptedPaymentMethods(): array
    {
        $methods = $this->payment_methods ?? [];
        $opts = array_keys(self::paymentMethodOptions());
        // Support both list ["credit_cards", "cash"] and associative ["credit_cards" => true]
        $keys = array_keys($methods) === range(0, count($methods) - 1) ? $methods : array_keys(array_filter($methods));
        return array_values(array_intersect($keys, $opts));
    }

    /**
     * Rate trend from history: compare latest vs previous GBP/IRR record.
     * Returns ['buy' => 'up'|'down'|null, 'sell' => 'up'|'down'|null].
     */
    public function getRateTrend(): array
    {
        $rows = $this->exchangeRateHistory()
            ->where('from_currency', 'GBP')
            ->where('to_currency', 'IRR')
            ->orderByDesc('recorded_at')
            ->limit(2)
            ->get();

        if ($rows->count() < 2) {
            return ['buy' => null, 'sell' => null];
        }

        $latest = $rows[0];
        $previous = $rows[1];
        $buyLatest = (float) $latest->buy_rate;
        $buyPrevious = (float) $previous->buy_rate;
        $sellLatest = (float) $latest->sell_rate;
        $sellPrevious = (float) $previous->sell_rate;

        $buy = $buyLatest > $buyPrevious ? 'up' : ($buyLatest < $buyPrevious ? 'down' : null);
        $sell = $sellLatest > $sellPrevious ? 'up' : ($sellLatest < $sellPrevious ? 'down' : null);

        return ['buy' => $buy, 'sell' => $sell];
    }

    public static function statusRules(): array
    {
        return [
            Rule::in([self::STATUS_DRAFT, self::STATUS_PENDING_KYC, self::STATUS_ACTIVE, self::STATUS_SUSPENDED]),
        ];
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_confirmed_at !== null;
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    public function getBlockedReason(): ?string
    {
        return $this->blocked_reason;
    }
}
