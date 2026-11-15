<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionPaymentStatusMail;
use App\Models\AuditLog;
use App\Models\SubscriptionPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class SubscriptionPaymentController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->query('status', 'all');

        $query = SubscriptionPayment::with(['tenant:id,name,email,subscription_plan,subscription_status', 'user:id,name,email'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $payments = $query->paginate(20)->withQueryString()->through(fn (SubscriptionPayment $p) => [
            'id' => $p->id,
            'tenant_name' => $p->tenant?->name ?? '-',
            'user_name' => $p->user?->name ?? '-',
            'user_email' => $p->user?->email ?? '-',
            'plan' => $p->plan,
            'billing_cycle' => $p->billing_cycle,
            'amount' => (float) $p->amount,
            'sender_name' => $p->sender_name,
            'sender_bank' => $p->sender_bank,
            'transfer_date' => $p->transfer_date?->format('d M Y'),
            'proof_url' => asset('storage/' . $p->proof_path),
            'status' => $p->status,
            'admin_notes' => $p->admin_notes,
            'created_at' => $p->created_at?->format('d M Y H:i'),
        ]);

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'filters' => [
                'status' => $status,
            ],
            'counts' => [
                'all' => SubscriptionPayment::count(),
                'pending' => SubscriptionPayment::where('status', 'pending')->count(),
                'approved' => SubscriptionPayment::where('status', 'approved')->count(),
                'rejected' => SubscriptionPayment::where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function verify(Request $request, SubscriptionPayment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($payment->status !== 'pending') {
            return back()->withErrors(['action' => 'Pembayaran ini sudah diverifikasi sebelumnya.']);
        }

        $newStatus = $validated['action'] === 'approve' ? 'approved' : 'rejected';

        DB::transaction(function () use ($payment, $newStatus, $validated, $request) {
            $payment->update([
                'status' => $newStatus,
                'admin_notes' => $validated['admin_notes'],
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);

            if ($newStatus === 'approved' && $payment->tenant) {
                $expiresAt = $payment->billing_cycle === 'annual'
                    ? now()->addYear()
                    : now()->addMonth();

                $payment->tenant->update([
                    'subscription_plan' => $payment->plan,
                    'subscription_status' => 'active',
                    'trial_ends_at' => $expiresAt,
                ]);
            }

            AuditLog::log(
                'subscription_payment_' . $newStatus,
                SubscriptionPayment::class,
                $payment->id,
                ['status' => 'pending'],
                ['status' => $newStatus, 'plan' => $payment->plan]
            );
        });

        $payment->load('user');

        if ($payment->user?->email) {
            try {
                Mail::to($payment->user->email)->send(new SubscriptionPaymentStatusMail($payment));
            } catch (Throwable $e) {
                Log::error('Gagal mengirim email status pembayaran ke user: ' . $e->getMessage());
            }
        }

        return back()->with('success', $newStatus === 'approved'
            ? 'Pembayaran disetujui dan paket langganan tenant telah diaktifkan.'
            : 'Pembayaran ditolak dan notifikasi telah dikirim ke pengguna.');
    }
}

