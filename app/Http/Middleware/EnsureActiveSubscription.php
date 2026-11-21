<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\PlanLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $request->user()?->tenant;

        if ($tenant && app(PlanLimitService::class)->isSubscriptionLocked($tenant)) {
            $message = 'Masa uji coba / langganan Anda telah berakhir. Data tetap aman, silakan aktifkan paket untuk melakukan transaksi.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 402);
            }

            return redirect()->route('billing.index')->with('error', $message);
        }

        return $next($request);
    }
}

