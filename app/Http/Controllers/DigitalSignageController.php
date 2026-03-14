<?php

namespace App\Http\Controllers;

use App\Models\DigitalSignageScreen;
use App\Models\ExchangeOffice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DigitalSignageController extends Controller
{
    protected function getOffice(): ?ExchangeOffice
    {
        $office = Auth::guard('exchange')->user();

        return $office instanceof ExchangeOffice ? $office : null;
    }

    protected function authorizeSignage(): ExchangeOffice
    {
        $office = $this->getOffice();
        if (! $office) {
            abort(403);
        }
        if (! $office->canUseDigitalSignage()) {
            abort(403, 'برای استفاده از صفحه نمایش دیجیتال باید پلنی که این امکان را دارد خریداری کنید.');
        }

        return $office;
    }

    protected function authorizeScreen(DigitalSignageScreen $screen): void
    {
        $office = $this->getOffice();
        if (! $office || $screen->exchange_office_id !== $office->id) {
            abort(403);
        }
    }

    public function index(): View|RedirectResponse
    {
        $office = $this->authorizeSignage();
        $screens = $office->digitalSignageScreens()->latest()->get();

        return view('dashboard.signage.index', [
            'office' => $office,
            'screens' => $screens,
        ]);
    }

    public function create(): View|RedirectResponse
    {
        $this->authorizeSignage();

        return view('dashboard.signage.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $office = $this->authorizeSignage();

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'background_color' => ['nullable', 'string', 'max:20'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:4096'],
            'qr_link' => ['nullable', 'string', 'max:500'],
        ]);

        $screen = $office->digitalSignageScreens()->create([
            'name' => $validated['name'] ?? null,
            'background_color' => $validated['background_color'] ?? null,
            'crypto_enabled' => true,
            'qr_link' => (filled($validated['qr_link'] ?? null) && filter_var($validated['qr_link'], FILTER_VALIDATE_URL)) ? $validated['qr_link'] : null,
        ]);

        if (! empty($validated['background_image'])) {
            $path = $request->file('background_image')->store('signage-backgrounds', 'public');
            $screen->update(['background_image_path' => $path]);
        }

        return redirect()
            ->route('dashboard.signage.index')
            ->with('success', 'صفحه نمایش با موفقیت ساخته شد. کد pairing: ' . $screen->pairing_code);
    }

    public function edit(DigitalSignageScreen $screen): View|RedirectResponse
    {
        $this->authorizeScreen($screen);
        $this->authorizeSignage();

        return view('dashboard.signage.edit', ['screen' => $screen]);
    }

    public function update(Request $request, DigitalSignageScreen $screen): RedirectResponse
    {
        $this->authorizeScreen($screen);
        $this->authorizeSignage();

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'background_color' => ['nullable', 'string', 'max:20'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:4096'],
            'remove_background_image' => ['nullable', 'boolean'],
            'crypto_enabled' => ['nullable', 'boolean'],
            'qr_link' => ['nullable', 'string', 'max:500'],
        ]);

        $screen->update([
            'name' => $validated['name'] ?? $screen->name,
            'background_color' => $validated['background_color'] ?? $screen->background_color,
            'crypto_enabled' => $request->boolean('crypto_enabled'),
            'qr_link' => (filled($validated['qr_link'] ?? null) && filter_var($validated['qr_link'], FILTER_VALIDATE_URL)) ? $validated['qr_link'] : null,
        ]);

        if (! empty($validated['remove_background_image'])) {
            if ($screen->background_image_path) {
                Storage::disk('public')->delete($screen->background_image_path);
            }
            $screen->update(['background_image_path' => null]);
        } elseif (! empty($validated['background_image'])) {
            if ($screen->background_image_path) {
                Storage::disk('public')->delete($screen->background_image_path);
            }
            $path = $request->file('background_image')->store('signage-backgrounds', 'public');
            $screen->update(['background_image_path' => $path]);
        }

        return redirect()
            ->route('dashboard.signage.index')
            ->with('success', 'صفحه نمایش به‌روزرسانی شد.');
    }

    public function destroy(DigitalSignageScreen $screen): RedirectResponse
    {
        $this->authorizeScreen($screen);
        $this->authorizeSignage();

        if ($screen->background_image_path) {
            Storage::disk('public')->delete($screen->background_image_path);
        }
        $screen->delete();

        return redirect()
            ->route('dashboard.signage.index')
            ->with('success', 'صفحه نمایش حذف شد.');
    }

    /**
     * Pair a screen by token (from QR scan) or by pairing_code (manual entry). Links unpaired screen to current office.
     */
    public function pair(Request $request): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $office = $this->authorizeSignage();

        $token = $request->input('token');
        $pairingCode = $request->input('pairing_code');

        if ($pairingCode) {
            $pairingCode = strtoupper(trim($pairingCode));
            $screen = DigitalSignageScreen::where('pairing_code', $pairingCode)->first();
            if ($screen) {
                $token = $screen->token;
            }
        }

        if (! $token && $request->input('display_url')) {
            $url = $request->input('display_url');
            if (preg_match('#/signage/([a-zA-Z0-9]+)(?:\?|$)#', $url, $m)) {
                $token = $m[1];
            }
        }

        if (! $token) {
            if ($pairingCode !== null && $pairingCode !== '') {
                $message = 'صفحه‌ای با این کد اتصال یافت نشد.';
            } else {
                $message = 'لینک یا توکن معتبر نیست.';
            }

            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : redirect()->route('dashboard.signage.index')->with('error', $message);
        }

        $screen = $screen ?? DigitalSignageScreen::where('token', $token)->first();
        if (! $screen) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'صفحه‌ای با این کد یافت نشد.'], 404)
                : redirect()->route('dashboard.signage.index')->with('error', 'صفحه‌ای با این کد یافت نشد.');
        }

        if ($screen->exchange_office_id && $screen->exchange_office_id !== $office->id) {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'این صفحه به صرافی دیگری متصل است.'], 403)
                : redirect()->route('dashboard.signage.index')->with('error', 'این صفحه به صرافی دیگری متصل است.');
        }

        if (! $screen->exchange_office_id) {
            $screen->update(['exchange_office_id' => $office->id]);
            $message = 'صفحه نمایش با موفقیت به حساب شما متصل شد.';
        } else {
            $message = 'این صفحه قبلاً به حساب شما متصل است.';
        }

        $redirectUrl = route('dashboard.signage.edit', $screen);

        return $request->wantsJson()
            ? response()->json(['success' => true, 'message' => $message, 'redirect' => $redirectUrl])
            : redirect()->to($redirectUrl)->with('success', $message);
    }
}
