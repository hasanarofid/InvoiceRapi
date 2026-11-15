<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\SubscriptionPaymentSubmittedMail;
use App\Models\SubscriptionPayment;
use App\Services\PlanLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class BillingController extends Controller
{
    public function __construct(
        private readonly PlanLimitService $planLimitService,
    ) {}

    public function index(Request $request): Response
    {
        $user = $request->user();
        $tenant = $user->tenant;

        $usage = $tenant ? $this->planLimitService->getUsageSummary($tenant) : null;

        $payments = $tenant
            ? SubscriptionPayment::where('tenant_id', $tenant->id)
                ->latest()
                ->get()
                ->map(fn (SubscriptionPayment $p) => [
                    'id' => $p->id,
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
                ])
            : [];

        return Inertia::render('Billing/Index', [
            'usage' => $usage,
            'bank' => PlanLimitService::PAYMENT_BANK,
            'plans' => PlanLimitService::PLANS,
            'payments' => $payments,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => 'required|in:pro,bisnis',
            'billing_cycle' => 'required|in:monthly,annual',
            'sender_name' => 'required|string|max:120',
            'sender_bank' => 'nullable|string|max:80',
            'transfer_date' => 'required|date',
            'proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:4096',
        ], [
            'plan.required' => 'Pilih paket langganan terlebih dahulu.',
            'sender_name.required' => 'Nama pemilik rekening pengirim wajib diisi.',
            'transfer_date.required' => 'Tanggal transfer wajib diisi.',
            'proof.required' => 'Bukti pembayaran wajib diunggah.',
            'proof.mimes' => 'Format bukti transfer harus JPG, PNG, WEBP, atau PDF.',
            'proof.max' => 'Ukuran file maksimal 4 MB.',
        ]);

        $user = $request->user();
        $tenant = $user->tenant;

        if (! $tenant) {
            return back()->withErrors(['plan' => 'Workspace bisnis belum terdaftar.']);
        }

        // Compute exact amount server-side
        $planConfig = PlanLimitService::PLANS[$validated['plan']];
        $amount = $validated['billing_cycle'] === 'annual'
            ? $planConfig['annual_price']
            : $planConfig['monthly_price'];

        $proofPath = $request->file('proof')->store('payment-proofs', 'public');

        $payment = SubscriptionPayment::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'plan' => $validated['plan'],
            'billing_cycle' => $validated['billing_cycle'],
            'amount' => $amount,
            'bank_name' => PlanLimitService::PAYMENT_BANK['bank_name'],
            'account_number' => PlanLimitService::PAYMENT_BANK['account_number'],
            'account_holder' => PlanLimitService::PAYMENT_BANK['account_holder'],
            'sender_name' => $validated['sender_name'],
            'sender_bank' => $validated['sender_bank'],
            'transfer_date' => $validated['transfer_date'],
            'proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        $payment->load(['tenant', 'user']);

        // Notify official admin email (synbarr.techno@gmail.com)
        try {
            $adminEmail = config('mail.from.address', 'synbarr.techno@gmail.com');
            Mail::to($adminEmail)->send(new SubscriptionPaymentSubmittedMail($payment));
        } catch (Throwable $e) {
            Log::error('Gagal mengirim email notifikasi bukti transfer ke admin: ' . $e->getMessage());
        }

        return redirect()->route('billing.index')->with('success', 'Bukti pembayaran berhasil diunggah! Tim kami akan memverifikasi dan mengaktifkan paket Anda.');
    }
}

