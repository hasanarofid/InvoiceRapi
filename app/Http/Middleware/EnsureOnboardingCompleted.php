<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     * Ensure that authenticated users have registered their business workspace and active WhatsApp before accessing app dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Superadmins are not restricted by tenant onboarding
            if ($user->isSuperAdmin()) {
                return $next($request);
            }

            // Exclude onboarding routes, profile editing, and logout
            if (
                $request->routeIs('onboarding.*') ||
                $request->routeIs('logout') ||
                $request->routeIs('profile.*')
            ) {
                return $next($request);
            }

            if (! $user->hasCompletedOnboarding()) {
                return redirect()->route('onboarding.index');
            }
        }

        return $next($request);
    }
}
