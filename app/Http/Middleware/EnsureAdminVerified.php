<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminVerified
{
    /**
     * Ensure authenticated superadmin has completed 2-step email OTP verification.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isSuperAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        if (! $request->session()->has('admin_verified_at')) {
            return redirect()->route('admin.verify.show');
        }

        return $next($request);
    }
}

