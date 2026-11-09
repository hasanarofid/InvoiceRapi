<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceDashboardController extends Controller
{
    /**
     * Display the member workspace dashboard according to the official mockup.
     */
    public function index(Request $request): Response|RedirectResponse
    {
        $user = auth()->user();

        // Superadmin belongs to system management HQ
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $tenant = $user->tenant;
        $tenantId = $tenant?->id;

        // Date range filtering (default: current year)
        $year = $request->query('year', date('Y'));

        // 1. Calculate 4 core financial stat cards
        $totalInvoiced = (float) Invoice::where('tenant_id', $tenantId)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('total');

        $totalPaid = (float) Invoice::where('tenant_id', $tenantId)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('total_paid');

        $totalOutstanding = (float) Invoice::where('tenant_id', $tenantId)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('balance_due');

        $totalOverdue = (float) Invoice::where('tenant_id', $tenantId)
            ->where('status', 'overdue')
            ->sum('balance_due');

        // Check if tenant has real data or fresh onboarding
        $hasInvoices = Invoice::where('tenant_id', $tenantId)->exists();

        // 2. Monthly Revenue Trend (Last 6 Months)
        $monthlyRevenue = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->translatedFormat('M');
            $months[] = $monthKey;

            $paidInMonth = (float) Payment::where('tenant_id', $tenantId)
                ->where('is_void', false)
                ->whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->sum('amount');

            $monthlyRevenue[] = [
                'month' => $monthKey,
                'amount' => $paidInMonth,
            ];
        }

        // 3. Invoice Status Breakdown (for Donut Chart)
        $statusCounts = [
            'paid' => Invoice::where('tenant_id', $tenantId)->where('status', 'paid')->count(),
            'partial' => Invoice::where('tenant_id', $tenantId)->where('status', 'partial')->count(),
            'unpaid' => Invoice::where('tenant_id', $tenantId)->whereIn('status', ['issued', 'overdue'])->count(),
            'draft' => Invoice::where('tenant_id', $tenantId)->where('status', 'draft')->count(),
        ];
        $totalCount = array_sum($statusCounts);

        // Subscription display string
        $trialEndsAt = $tenant?->trial_ends_at 
            ? Carbon::parse($tenant->trial_ends_at)->translatedFormat('d M Y') 
            : Carbon::now()->addDays(14)->translatedFormat('d M Y');

        return Inertia::render('Dashboard', [
            'tenant' => [
                'name' => $tenant?->name ?: 'Workspace Bisnis',
                'business_type' => $tenant?->business_type ?: 'Jasa Profesional',
                'plan' => ucfirst($tenant?->subscription_plan ?: 'Pro'),
                'expires_at' => $trialEndsAt,
                'has_real_data' => $hasInvoices,
            ],
            'stats' => [
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'total_outstanding' => $totalOutstanding,
                'total_overdue' => $totalOverdue,
            ],
            'charts' => [
                'monthly_revenue' => $monthlyRevenue,
                'status_breakdown' => [
                    'total' => $totalCount,
                    'paid' => $statusCounts['paid'],
                    'partial' => $statusCounts['partial'],
                    'unpaid' => $statusCounts['unpaid'],
                    'draft' => $statusCounts['draft'],
                ],
            ],
        ]);
    }
}

