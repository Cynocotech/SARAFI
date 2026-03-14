<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotExchange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('exchange')->check()) {
            return redirect()->route('exchange.login');
        }

        return $next($request);
    }
}
