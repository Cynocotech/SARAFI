<?php

namespace App\Http\Controllers;

use App\Models\ExchangeOffice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonateExchangeController extends Controller
{
    /**
     * Start impersonating an exchange (admin only). Logs in as the exchange and redirects to dashboard.
     */
    public function impersonate(Request $request, ExchangeOffice $exchangeOffice): RedirectResponse
    {
        if (! Auth::guard('web')->check()) {
            abort(403, 'Only logged-in admins can impersonate.');
        }

        Session::put('impersonating', true);
        Session::put('impersonation_admin_id', Auth::guard('web')->id());

        Auth::guard('exchange')->login($exchangeOffice);

        return redirect()->route('dashboard.index');
    }

    /**
     * Stop impersonating: log out from exchange and redirect back to admin panel.
     */
    public function leave(Request $request): RedirectResponse
    {
        Auth::guard('exchange')->logout();
        Session::forget(['impersonating', 'impersonation_admin_id']);

        return redirect()->route('filament.admin.pages.dashboard');
    }
}
