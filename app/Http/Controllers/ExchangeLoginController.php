<?php

namespace App\Http\Controllers;

use App\Models\ExchangeOffice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExchangeLoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('exchange.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'نام کاربری را وارد کنید.',
            'password.required' => 'رمز عبور را وارد کنید.',
        ]);

        $office = ExchangeOffice::where('username', $validated['username'])->first();

        if (! $office || ! Auth::guard('exchange')->getProvider()->validateCredentials($office, ['password' => $validated['password']])) {
            throw ValidationException::withMessages([
                'username' => ['نام کاربری یا رمز عبور اشتباه است.'],
            ]);
        }

        if ($office->isBlocked()) {
            $message = 'این حساب مسدود شده است.';
            if ($office->getBlockedReason()) {
                $message .= ' دلیل: ' . $office->getBlockedReason();
            }
            throw ValidationException::withMessages([
                'username' => [$message],
            ]);
        }

        if ($office->hasTwoFactorEnabled()) {
            $request->session()->put('exchange_2fa_office_id', $office->id);
            $request->session()->put('exchange_2fa_remember', (bool) $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('exchange.2fa.challenge');
        }

        Auth::guard('exchange')->login($office, (bool) $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('exchange')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('exchange.login');
    }
}
