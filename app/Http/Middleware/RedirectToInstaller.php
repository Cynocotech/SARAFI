<?php

namespace App\Http\Middleware;

use App\Http\Controllers\InstallController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToInstaller
{
    /**
     * Redirect to installer if the application is not yet installed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('install') || $request->is('install/*')) {
            return $next($request);
        }

        if (InstallController::isInstalled()) {
            return $next($request);
        }

        return redirect()->route('install.requirements');
    }
}
