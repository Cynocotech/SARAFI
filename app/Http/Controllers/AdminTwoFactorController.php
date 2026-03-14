<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminTwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactor
    ) {}

    /**
     * Show 2FA verification form (after login when admin has 2FA enabled).
     */
    public function showVerify(Request $request): View|RedirectResponse
    {
        if (! Auth::guard('web')->check()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }
        $user = Auth::guard('web')->user();
        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }
        if ($request->session()->get('admin_2fa_verified')) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        return view('admin.2fa-verify');
    }

    /**
     * Verify 2FA code and set session, then redirect to admin.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        if (! $user || ! $user->hasTwoFactorEnabled()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        $request->validate(['code' => ['required', 'string', 'size:6']], [
            'code.required' => 'Please enter the 2FA code.',
            'code.size' => 'The code must be 6 digits.',
        ]);

        $secret = $user->two_factor_secret;
        try {
            $secret = decrypt($secret);
        } catch (\Throwable) {
            // plain
        }
        if (! $this->twoFactor->verify($secret, $request->input('code'))) {
            throw ValidationException::withMessages(['code' => ['Invalid code. Please enter the current code from your app.']]);
        }

        $request->session()->put('admin_2fa_verified', true);

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }

    /**
     * Show "2FA enabled" confirmation page after first-time setup.
     */
    public function showEnabled(Request $request): View|RedirectResponse
    {
        $user = Auth::guard('web')->user();
        if (! $user || ! $user->hasTwoFactorEnabled()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        return view('admin.2fa-enabled');
    }

    /**
     * Show 2FA setup form (enable 2FA for admin).
     */
    public function showSetup(Request $request): View|RedirectResponse
    {
        $user = Auth::guard('web')->user();
        if (! $user) {
            return redirect()->route('filament.admin.pages.dashboard');
        }
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        $secret = $request->session()->get('admin_2fa_pending_secret');
        if (! $secret) {
            $secret = $this->twoFactor->generateSecret();
            $request->session()->put('admin_2fa_pending_secret', $secret);
        }

        $issuer = config('app.name', 'آقای صرافی');
        $accountName = $user->email ?: $user->name;
        $qrUrl = $this->twoFactor->getQRCodeUrl($issuer, $accountName, $secret);
        $qrSvg = $this->twoFactor->getQRCodeSvg($qrUrl);

        return view('admin.2fa-setup', [
            'secret' => $secret,
            'qrSvg' => $qrSvg,
        ]);
    }

    /**
     * Confirm 2FA setup and save to user.
     */
    public function confirmSetup(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        if (! $user || $user->hasTwoFactorEnabled()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        $secret = $request->session()->get('admin_2fa_pending_secret');
        if (! $secret) {
            return redirect()->route('admin.2fa.setup')->withErrors(['code' => 'Please start the setup again.']);
        }

        $request->validate(['code' => ['required', 'string', 'size:6']], [
            'code.required' => 'Please enter the code.',
            'code.size' => 'The code must be 6 digits.',
        ]);

        if (! $this->twoFactor->verify($secret, $request->input('code'))) {
            throw ValidationException::withMessages(['code' => ['Invalid code. Please enter the current code from your app.']]);
        }

        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ]);
        $request->session()->forget('admin_2fa_pending_secret');

        return redirect()->route('admin.2fa.enabled');
    }
}
