<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Setting;
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
            'features' => [
                'dp_termin' => false,
                'auto_reminder' => false,
                'receivables_report' => false,
                'custom_public_link' => false,
                'csv_export_audit' => false,
            ],
        ],
        'pro' => [
            'name' => 'Pro',
            'max_invoices_per_month' => 100,
            'max_clients' => null, // unlimited
            'max_users' => 2,
            'monthly_price' => 55000, // Promo (normal Rp 99.000)
            'annual_price' => 468000, // Rp 39.000 x 12
            'features' => [
                'dp_termin' => true,
                'auto_reminder' => true,
                'receivables_report' => true,
                'custom_public_link' => true,
                'csv_export_audit' => false,
            ],
        ],
        'bisnis' => [
            'name' => 'Bisnis',
            'max_invoices_per_month' => 500,
            'max_clients' => null, // unlimited
            'max_users' => 5,
            'monthly_price' => 119000, // Promo (normal Rp 199.000)
            'annual_price' => 996000, // Rp 83.000 x 12
            'features' => [
                'dp_termin' => true,
                'auto_reminder' => true,
                'receivables_report' => true,
                'custom_public_link' => true,
                'csv_export_audit' => true,
            ],
        ],
    ];

    /**
     * Return active plan configurations (DB overrides merged over defaults).
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getPlans(): array
    {
        $raw = Setting::getValue('invoicerapi_plans');
        if (! $raw) {
            return self::PLANS;
        }

        $decoded = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : null);
        if (! is_array($decoded)) {
            return self::PLANS;
        }

        return array_replace_recursive(self::PLANS, $decoded);
    }

    public function resolveEffectivePlan(Tenant $tenant): string
    {
        $plans = self::getPlans();
        $rawPlan = strtolower((string) ($tenant->subscription_plan ?: 'gratis'));
        if ($rawPlan === 'free') {
            $rawPlan = 'gratis';
        }

        if ($tenant->subscription_status === 'trial') {
            if ($tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
                return 'gratis';
            }
            return isset($plans[$rawPlan]) ? $rawPlan : 'pro';
        }

        if (in_array($tenant->subscription_status, ['expired', 'cancelled', 'past_due'], true)) {
            return 'gratis';
        }

        return isset($plans[$rawPlan]) ? $rawPlan : 'gratis';
    }

    public function getUsageSummary(Tenant $tenant): array
    {
        $plans = self::getPlans();
        $effectivePlan = $this->resolveEffectivePlan($tenant);
        $limits = $plans[$effectivePlan] ?? self::PLANS['gratis'];

        $invoicesThisMonth = Invoice::where('tenant_id', $tenant->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $clientsCount = Client::where('tenant_id', $tenant->id)
            ->where('is_archived', false)
            ->count();

        $usersCount = User::where('tenant_id', $tenant->id)->count();

        $invoiceLimit = $limits['max_invoices_per_month'];
        $clientLimit = $limits['max_clients'];

        $invoiceRemaining = $invoiceLimit !== null ? max(0, $invoiceLimit - $invoicesThisMonth) : null;
        $clientRemaining = $clientLimit !== null ? max(0, $clientLimit - $clientsCount) : null;

        $invoiceReached = $invoiceLimit !== null && $invoicesThisMonth >= $invoiceLimit;
        $clientReached = $clientLimit !== null && $clientsCount >= $clientLimit;

        $isTrial = $tenant->subscription_status === 'trial';
        $isTrialExpired = $isTrial
            && $tenant->trial_ends_at !== null
            && $tenant->trial_ends_at->isPast();

        $daysRemaining = null;
        $expiresAtFormatted = 'Gratis Selamanya';

        if ($effectivePlan !== 'gratis' && $tenant->trial_ends_at) {
            $daysRemaining = max(0, (int) now()->startOfDay()->diffInDays($tenant->trial_ends_at->copy()->startOfDay(), false));
            $expiresAtFormatted = $tenant->trial_ends_at->translatedFormat('d M Y');
        } elseif ($isTrialExpired && $tenant->trial_ends_at) {
            $daysRemaining = 0;
            $expiresAtFormatted = 'Berakhir ' . $tenant->trial_ends_at->translatedFormat('d M Y');
        }

        return [
            'plan' => $effectivePlan,
            'plan_name' => $limits['name'],
            'subscription_status' => $effectivePlan === 'gratis' && $isTrialExpired ? 'expired' : $tenant->subscription_status,
            'is_trial' => $isTrial && ! $isTrialExpired,
            'is_trial_expired' => $isTrialExpired,
            'is_subscription_locked' => $this->isSubscriptionLocked($tenant),
            'trial_ends_at' => $tenant->trial_ends_at?->toIso8601String(),
            'expires_at_formatted' => $expiresAtFormatted,
            'days_remaining' => $daysRemaining,
            'invoices_this_month' => $invoicesThisMonth,
            'max_invoices_per_month' => $invoiceLimit,
            'invoices_remaining' => $invoiceRemaining,
            'invoice_limit_reached' => $invoiceReached,
            'clients_count' => $clientsCount,
            'max_clients' => $clientLimit,
            'clients_remaining' => $clientRemaining,
            'client_limit_reached' => $clientReached,
            'users_count' => $usersCount,
            'max_users' => $limits['max_users'],
            'any_limit_reached' => $invoiceReached || $clientReached,
            'features' => $limits['features'],
        ];
    }

    public function isSubscriptionLocked(Tenant $tenant): bool
    {
        if ($tenant->subscription_status === 'trial') {
            return $tenant->trial_ends_at !== null && $tenant->trial_ends_at->isPast();
        }

        return in_array($tenant->subscription_status, ['expired', 'cancelled', 'past_due'], true);
    }

    public function canCreateInvoice(Tenant $tenant): array
    {
        $usage = $this->getUsageSummary($tenant);
        if ($usage['is_subscription_locked']) {
            return [
                'allowed' => false,
                'message' => 'Masa uji coba atau langganan Anda telah berakhir. Silakan aktifkan paket untuk membuat invoice baru.',
            ];
        }
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
        if ($usage['is_subscription_locked']) {
            return [
                'allowed' => false,
                'message' => 'Masa uji coba atau langganan Anda telah berakhir. Silakan aktifkan paket untuk menambah klien baru.',
            ];
        }
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
