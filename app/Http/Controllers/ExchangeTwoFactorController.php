<?php

namespace App\Http\Controllers;

use App\Models\ExchangeOffice;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExchangeTwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactor
    ) {}

    /**
     * Show 2FA challenge form (after password login when 2FA is enabled).
     */
    public function showChallenge(Request $request): View|RedirectResponse
    {
        $officeId = $request->session()->get('exchange_2fa_office_id');
        if (! $officeId) {
            return redirect()->route('exchange.login');
        }

        $office = ExchangeOffice::find($officeId);
        if (! $office) {
            $request->session()->forget(['exchange_2fa_office_id', 'exchange_2fa_remember']);
            return redirect()->route('exchange.login');
        }

        return view('exchange.2fa-challenge', ['office' => $office]);
    }

    /**
     * Verify 2FA code and complete exchange login.
     */
    public function verifyChallenge(Request $request): RedirectResponse
    {
        $officeId = $request->session()->get('exchange_2fa_office_id');
        if (! $officeId) {
            return redirect()->route('exchange.login');
        }

        $office = ExchangeOffice::find($officeId);
        if (! $office || ! $office->two_factor_secret) {
            $request->session()->forget(['exchange_2fa_office_id', 'exchange_2fa_remember']);
            throw ValidationException::withMessages(['code' => ['Please sign in again.']]);
        }

        $request->validate(['code' => ['required', 'string', 'size:6']], [
            'code.required' => 'Please enter the 2FA code.',
            'code.size' => 'The code must be 6 digits.',
        ]);

        $secret = $office->two_factor_secret;
        try {
            $secret = decrypt($secret);
        } catch (\Throwable) {
            // stored as plain (legacy or testing)
        }
        if (! $this->twoFactor->verify($secret, $request->input('code'))) {
            throw ValidationException::withMessages(['code' => ['Invalid code. Please enter the current code from your app.']]);
        }

        $remember = (bool) $request->session()->get('exchange_2fa_remember', false);
        $request->session()->forget(['exchange_2fa_office_id', 'exchange_2fa_remember']);
        Auth::guard('exchange')->login($office, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    /**
     * Show "2FA enabled" confirmation page after first-time setup.
     */
    public function showEnabled(Request $request): View|RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice || ! $office->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard.index');
        }

        return view('exchange.2fa-enabled');
    }

    /**
     * Show 2FA setup (enable) form when logged in as exchange.
     */
    public function showSetup(Request $request): View|RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            return redirect()->route('dashboard.index');
        }
        if ($office->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard.index')->with('info', 'Two-factor authentication is already enabled.');
        }

        $secret = $request->session()->get('exchange_2fa_pending_secret');
        if (! $secret) {
            $secret = $this->twoFactor->generateSecret();
            $request->session()->put('exchange_2fa_pending_secret', $secret);
        }

        $issuer = config('app.name', 'آقای صرافی');
        $accountName = $office->username ?: $office->name;
        $qrUrl = $this->twoFactor->getQRCodeUrl($issuer, $accountName, $secret);
        $qrSvg = $this->twoFactor->getQRCodeSvg($qrUrl);

        return view('exchange.2fa-setup', [
            'office' => $office,
            'secret' => $secret,
            'qrSvg' => $qrSvg,
        ]);
    }

    /**
     * Confirm 2FA setup with a code and save secret.
     */
    public function confirmSetup(Request $request): RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            return redirect()->route('dashboard.index');
        }
        if ($office->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard.index');
        }

        $secret = $request->session()->get('exchange_2fa_pending_secret');
        if (! $secret) {
            return redirect()->route('dashboard.2fa.setup')->withErrors(['code' => 'Please start the setup again.']);
        }

        $request->validate(['code' => ['required', 'string', 'size:6']], [
            'code.required' => 'Please enter the code.',
            'code.size' => 'The code must be 6 digits.',
        ]);

        if (! $this->twoFactor->verify($secret, $request->input('code'))) {
            throw ValidationException::withMessages(['code' => ['Invalid code. Please enter the current code from your app.']]);
        }

        $office->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ]);
        $request->session()->forget('exchange_2fa_pending_secret');

        return redirect()->route('dashboard.2fa.enabled');
    }
}
