<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    /**
     * Set application locale to Farsi (fa) for the admin panel so Filament
     * uses Persian translations and RTL (direction from filament-panels lang).
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('fa');

        return $next($request);
    }
}
