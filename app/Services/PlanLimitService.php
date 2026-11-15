<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\User;

class PlanLimitService
{
    /**
     * Bank account details for manual subscription transfer.
     */
    public const PAYMENT_BANK = [
        'bank_name' => 'Bank Central Asia (BCA)',
        'account_number' => '4650499269',
        'account_holder' => 'Akhmad Hasan Arofid',
    ];

    /**
     * Plan definitions aligned with pricing table.
     */
    public const PLANS = [
        'gratis' => [
            'name' => 'Gratis',
            'max_invoices_per_month' => 5,
            'max_clients' => 10,
            'max_users' => 1,
            'monthly_price' => 0,
            'annual_price' => 0,
        ],
        'pro' => [
            'name' => 'Pro',
            'max_invoices_per_month' => 100,
            'max_clients' => null, // unlimited
            'max_users' => 2,
            'monthly_price' => 99000,
            'annual_price' => 828000, // Rp 69.000 x 12
        ],
        'bisnis' => [
            'name' => 'Bisnis',
            'max_invoices_per_month' => 500,
            'max_clients' => null, // unlimited
            'max_users' => 5,
            'monthly_price' => 199000,
            'annual_price' => 1668000, // Rp 139.000 x 12
        ],
    ];

    public function resolveEffectivePlan(Tenant $tenant): string
    {
        $rawPlan = strtolower((string) ($tenant->subscription_plan ?: 'gratis'));
        if ($rawPlan === 'free') {
            $rawPlan = 'gratis';
        }

        if ($tenant->subscription_status === 'trial') {
            if ($tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
                return 'gratis';
            }
            return isset(self::PLANS[$rawPlan]) ? $rawPlan : 'pro';
        }

        if (in_array($tenant->subscription_status, ['expired', 'cancelled', 'past_due'], true)) {
            return 'gratis';
        }

        return isset(self::PLANS[$rawPlan]) ? $rawPlan : 'gratis';
    }

    public function getUsageSummary(Tenant $tenant): array
    {
        $effectivePlan = $this->resolveEffectivePlan($tenant);
        $limits = self::PLANS[$effectivePlan];

        $invoicesThisMonth = Invoice::where('tenant_id', $tenant->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $clientsCount = Client::where('tenant_id', $tenant->id)
            ->where('is_archived', false)
            ->count();

        $usersCount = User::where('tenant_id', $tenant->id)->count();

        $invoiceLimit = $limits['max_invoices_per_month'];
        $clientLimit = $limits['max_clients'];

        $invoiceReached = $invoiceLimit !== null && $invoicesThisMonth >= $invoiceLimit;
        $clientReached = $clientLimit !== null && $clientsCount >= $clientLimit;

        $isTrialExpired = $tenant->subscription_status === 'trial'
            && $tenant->trial_ends_at !== null
            && $tenant->trial_ends_at->isPast();

        return [
            'plan' => $effectivePlan,
            'plan_name' => $limits['name'],
            'subscription_status' => $tenant->subscription_status,
            'is_trial_expired' => $isTrialExpired,
            'trial_ends_at' => $tenant->trial_ends_at?->toIso8601String(),
            'invoices_this_month' => $invoicesThisMonth,
            'max_invoices_per_month' => $invoiceLimit,
            'invoice_limit_reached' => $invoiceReached,
            'clients_count' => $clientsCount,
            'max_clients' => $clientLimit,
            'client_limit_reached' => $clientReached,
            'users_count' => $usersCount,
            'max_users' => $limits['max_users'],
            'any_limit_reached' => $invoiceReached || $clientReached,
        ];
    }

    public function canCreateInvoice(Tenant $tenant): array
    {
        $usage = $this->getUsageSummary($tenant);
        if ($usage['invoice_limit_reached']) {
            return [
                'allowed' => false,
                'message' => sprintf(
                    'Kuota invoice bulan ini sudah penuh (%d/%d untuk Paket %s). Upgrade ke Paket Pro atau Bisnis untuk membuat lebih banyak invoice.',
                    $usage['invoices_this_month'],
                    $usage['max_invoices_per_month'],
                    $usage['plan_name']
                ),
            ];
        }

        return ['allowed' => true, 'message' => null];
    }

    public function canCreateClient(Tenant $tenant): array
    {
        $usage = $this->getUsageSummary($tenant);
        if ($usage['client_limit_reached']) {
            return [
                'allowed' => false,
                'message' => sprintf(
                    'Kuota klien terdaftar sudah mencapai batas (%d/%d untuk Paket %s). Upgrade ke Paket Pro untuk klien tanpa batas.',
                    $usage['clients_count'],
                    $usage['max_clients'],
                    $usage['plan_name']
                ),
            ];
        }

        return ['allowed' => true, 'message' => null];
    }
}

