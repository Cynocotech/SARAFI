<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }
        if (! $user->hasTwoFactorEnabled()) {
            return $next($request);
        }
        if ($request->session()->get('admin_2fa_verified')) {
            return $next($request);
        }

        return redirect()->route('admin.2fa.verify');
    }
}
