<?php

namespace App\Http\Middleware;

use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\SubscriptionPayment;
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
            'notifications' => fn () => $this->buildNotifications($user, $planUsage),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }

    /**
     * Build lightweight, role-scoped notifications for the top-right bell.
     *
     * @return array<int, array<string, string>>
     */
    private function buildNotifications($user, ?array $planUsage): array
    {
        if (! $user) {
            return [];
        }

        $items = [];

        if ($user->isSuperAdmin()) {
            $pendingPayments = SubscriptionPayment::with('tenant')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            foreach ($pendingPayments as $pay) {
                $items[] = [
                    'id' => "admin-pay-{$pay->id}",
                    'type' => 'payment_verify',
                    'title' => 'Verifikasi Upgrade Paket',
                    'message' => ($pay->tenant?->name ?? 'Member') . ' mengajukan paket ' . strtoupper((string) $pay->plan) . '.',
                    'href' => route('admin.payments.index'),
                    'time' => $pay->created_at?->diffForHumans() ?? '',
                ];
            }

            $recentLeads = Lead::latest()->take(3)->get();
            foreach ($recentLeads as $lead) {
                $items[] = [
                    'id' => "admin-lead-{$lead->id}",
                    'type' => 'lead',
                    'title' => 'Prospek Baru Masuk',
                    'message' => $lead->name . ($lead->company ? " ({$lead->company})" : ''),
                    'href' => route('admin.leads.index'),
                    'time' => $lead->created_at?->diffForHumans() ?? '',
                ];
            }

            $pendingOrders = Order::where('status', 'pending')->latest()->take(3)->get();
            foreach ($pendingOrders as $order) {
                $items[] = [
                    'id' => "admin-order-{$order->id}",
                    'type' => 'order',
                    'title' => 'Pesanan Baru Menunggu',
                    'message' => "Order #{$order->order_number}",
                    'href' => route('admin.orders.index'),
                    'time' => $order->created_at?->diffForHumans() ?? '',
                ];
            }

            return $items;
        }

        if ($user->tenant_id) {
            $overdueInvoices = Invoice::with('client')
                ->where('tenant_id', $user->tenant_id)
                ->whereNotIn('status', ['paid', 'cancelled', 'draft'])
                ->whereDate('due_date', '<', now()->toDateString())
                ->orderBy('due_date')
                ->take(4)
                ->get();

            foreach ($overdueInvoices as $inv) {
                $items[] = [
                    'id' => "inv-overdue-{$inv->id}",
                    'type' => 'overdue',
                    'title' => "Invoice Terlambat: {$inv->invoice_number}",
                    'message' => ($inv->client?->name ?? 'Klien') . ' jatuh tempo ' . ($inv->due_date?->format('d M Y') ?? '-') . '.',
                    'href' => route('invoices.show', $inv->id),
                    'time' => 'Terlambat',
                ];
            }

            $dueSoonInvoices = Invoice::with('client')
                ->where('tenant_id', $user->tenant_id)
                ->whereNotIn('status', ['paid', 'cancelled', 'draft'])
                ->whereBetween('due_date', [now()->toDateString(), now()->addDays(3)->toDateString()])
                ->orderBy('due_date')
                ->take(4)
                ->get();

            foreach ($dueSoonInvoices as $inv) {
                $items[] = [
                    'id' => "inv-soon-{$inv->id}",
                    'type' => 'due_soon',
                    'title' => "Segera Jatuh Tempo: {$inv->invoice_number}",
                    'message' => ($inv->client?->name ?? 'Klien') . ' tempo pada ' . ($inv->due_date?->format('d M Y') ?? '-') . '.',
                    'href' => route('invoices.show', $inv->id),
                    'time' => 'Segera',
                ];
            }

            $latestSub = SubscriptionPayment::where('tenant_id', $user->tenant_id)
                ->latest()
                ->first();

            if ($latestSub) {
                $statusLabel = match ($latestSub->status) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu Verifikasi',
                };
                $items[] = [
                    'id' => "sub-{$latestSub->id}-{$latestSub->status}",
                    'type' => 'billing',
                    'title' => "Pembayaran Paket ({$statusLabel})",
                    'message' => 'Paket ' . strtoupper((string) $latestSub->plan) . " — status: {$statusLabel}.",
                    'href' => route('billing.index'),
                    'time' => $latestSub->updated_at?->diffForHumans() ?? '',
                ];
            }

            if ($planUsage && ($planUsage['any_limit_reached'] || $planUsage['is_trial_expired'])) {
                $items[] = [
                    'id' => 'quota-warning-' . ($planUsage['plan_slug'] ?? 'free'),
                    'type' => 'quota',
                    'title' => 'Status Kuota & Paket',
                    'message' => $planUsage['is_trial_expired']
                        ? 'Masa coba 14 hari berakhir. Upgrade untuk membuka fitur Pro.'
                        : 'Kuota bulanan hampir/sudah penuh. Upgrade paket Anda.',
                    'href' => route('billing.index'),
                    'time' => 'Penting',
                ];
            }
        }

        return $items;
    }
}
