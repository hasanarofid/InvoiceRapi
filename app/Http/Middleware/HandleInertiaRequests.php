<?php

namespace App\Http\Middleware;

use App\Services\PlanLimitService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user()?->loadMissing('tenant');
        $planUsage = null;

        if ($user && $user->tenant) {
            $planUsage = app(PlanLimitService::class)->getUsageSummary($user->tenant);
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'planUsage' => $planUsage,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
