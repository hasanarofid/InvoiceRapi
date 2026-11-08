<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrialTrackerController extends Controller
{
    /**
     * Display a listing of user trials and lead statuses.
     */
    public function index(Request $request): Response
    {
        $statusFilter = $request->query('status', 'all'); // all, trial_active, expiring_soon, expired, paid
        $search = $request->query('search', '');

        $query = Tenant::with(['users' => function ($q) {
            $q->where('role', 'tenant_admin')->orWhere('role', 'owner');
        }])->latest();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_whatsapp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $now = Carbon::now();

        $tenants = $query->paginate(20)->through(function (Tenant $tenant) use ($now) {
            $owner = $tenant->users->first();
            
            $daysRemaining = null;
            $urgency = 'normal'; // normal, warning, critical, expired, paid

            if ($tenant->subscription_status === 'active') {
                $urgency = 'paid';
            } elseif ($tenant->trial_ends_at) {
                $endsAt = Carbon::parse($tenant->trial_ends_at);
                $diffDays = (int) $now->diffInDays($endsAt, false);

                if ($now->greaterThan($endsAt)) {
                    $daysRemaining = 0;
                    $urgency = 'expired';
                } else {
                    $daysRemaining = max(1, $diffDays);
                    if ($daysRemaining <= 3) {
                        $urgency = 'critical'; // <= 3 days remaining
                    } elseif ($daysRemaining <= 7) {
                        $urgency = 'warning'; // <= 7 days remaining
                    }
                }
            }

            // WhatsApp template generator
            $ownerName = $owner ? $owner->name : 'Kak';
            $bizName = $tenant->name;
            
            if ($urgency === 'critical' || $urgency === 'warning') {
                $waText = "Halo Kak {$ownerName} dari {$bizName}, salam dari tim Synbarra InvoiceRapi 👋\n\nMasa uji coba (free trial) 14 hari Paket Pro Anda tersisa *{$daysRemaining} hari lagi*. Apakah alur pembuatan invoice dan termin DP untuk klien berjalan lancar? Mau kami bantu untuk aktivasi paket langganan resmi dengan promo khusus?";
            } elseif ($urgency === 'expired') {
                $waText = "Halo Kak {$ownerName} dari {$bizName}, masa uji coba InvoiceRapi Anda telah berakhir hari ini. Agar pengelolaan invoice klien dan pencatatan pembayaran tetap rapi tanpa jeda, yuk aktivasi langganan Pro Anda sekarang!";
            } else {
                $waText = "Halo Kak {$ownerName} dari {$bizName}, terima kasih telah mencoba Synbarra InvoiceRapi! Jika butuh panduan setup termin atau invoice perdana, tim kami siap membantu ya kak.";
            }

            $waLink = "https://wa.me/{$tenant->phone_whatsapp}?text=" . rawurlencode($waText);

            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'business_type' => $tenant->business_type ?: 'Belum diatur',
                'phone_whatsapp' => $tenant->phone_whatsapp,
                'email' => $tenant->email,
                'owner_name' => $ownerName,
                'subscription_plan' => ucfirst($tenant->subscription_plan),
                'subscription_status' => $tenant->subscription_status,
                'trial_starts_at' => $tenant->trial_starts_at ? Carbon::parse($tenant->trial_starts_at)->format('d M Y') : '-',
                'trial_ends_at' => $tenant->trial_ends_at ? Carbon::parse($tenant->trial_ends_at)->format('d M Y') : '-',
                'days_remaining' => $daysRemaining,
                'urgency' => $urgency,
                'last_follow_up_at' => $tenant->last_follow_up_at ? Carbon::parse($tenant->last_follow_up_at)->diffForHumans() : 'Belum pernah',
                'follow_up_notes' => $tenant->follow_up_notes,
                'wa_link' => $waLink,
            ];
        });

        // Filter collections if specific status selected
        if ($statusFilter === 'expiring_soon') {
            $tenants->setCollection($tenants->getCollection()->where('urgency', 'critical'));
        } elseif ($statusFilter === 'expired') {
            $tenants->setCollection($tenants->getCollection()->where('urgency', 'expired'));
        } elseif ($statusFilter === 'paid') {
            $tenants->setCollection($tenants->getCollection()->where('urgency', 'paid'));
        }

        // Summary metrics
        $totalTrials = Tenant::where('subscription_status', 'trial')->count();
        $expiringSoon = Tenant::where('subscription_status', 'trial')
            ->whereBetween('trial_ends_at', [$now, $now->copy()->addDays(3)])
            ->count();
        $expiredCount = Tenant::where('subscription_status', 'trial')
            ->where('trial_ends_at', '<', $now)
            ->count();
        $paidCount = Tenant::where('subscription_status', 'active')->count();

        return Inertia::render('Admin/TrialTracker/Index', [
            'tenants' => $tenants,
            'filters' => [
                'status' => $statusFilter,
                'search' => $search,
            ],
            'metrics' => [
                'total_trials' => $totalTrials,
                'expiring_soon' => $expiringSoon,
                'expired' => $expiredCount,
                'paid' => $paidCount,
            ],
        ]);
    }

    /**
     * Log follow-up progress for a tenant.
     */
    public function updateFollowUp(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $tenant->update([
            'last_follow_up_at' => now(),
            'follow_up_notes' => $request->notes,
        ]);

        return back()->with('success', "Status follow-up untuk {$tenant->name} berhasil diperbarui.");
    }
}

