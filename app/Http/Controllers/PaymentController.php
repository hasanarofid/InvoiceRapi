<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentInstallment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Record a payment for an invoice or specific installment.
     */
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;
        if ($invoice->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'payment_installment_id' => 'nullable|exists:payment_installments,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|in:bank_transfer,cash,qris,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($tenantId, $invoice, $validated) {
            $payment = Payment::create([
                'tenant_id' => $tenantId,
                'invoice_id' => $invoice->id,
                'payment_installment_id' => $validated['payment_installment_id'] ?? null,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // If linked to installment, update installment status
            if (! empty($validated['payment_installment_id'])) {
                $installment = PaymentInstallment::where('invoice_id', $invoice->id)
                    ->findOrFail($validated['payment_installment_id']);

                $paidOnInstallment = Payment::where('payment_installment_id', $installment->id)
                    ->where('is_void', false)
                    ->sum('amount');

                $newInstStatus = $paidOnInstallment >= (float) $installment->amount ? 'paid' : ($paidOnInstallment > 0 ? 'partial' : 'unpaid');

                $installment->update([
                    'paid_amount' => $paidOnInstallment,
                    'status' => $newInstStatus,
                ]);
            }

            // Recalculate invoice overall balance & status
            $invoice->recalculateBalances();

            AuditLog::log('payment_recorded', Payment::class, $payment->id, null, $payment->toArray());
        });

        return redirect()->back()->with('success', 'Pembayaran berhasil dicatat dan saldo tagihan telah diperbarui.');
    }

    /**
     * Void a payment (never hard delete).
     */
    public function void(Request $request, Payment $payment): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;
        if ($payment->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'void_reason' => 'required|string|min:5|max:255',
        ]);

        DB::transaction(function () use ($payment, $request) {
            $oldValues = $payment->toArray();

            $payment->update([
                'is_void' => true,
                'void_reason' => $request->void_reason,
                'voided_at' => now(),
                'voided_by' => auth()->id(),
            ]);

            // Re-update installment status if applicable
            if ($payment->payment_installment_id) {
                $installment = $payment->installment;
                if ($installment) {
                    $paidOnInstallment = Payment::where('payment_installment_id', $installment->id)
                        ->where('is_void', false)
                        ->sum('amount');

                    $newInstStatus = $paidOnInstallment >= (float) $installment->amount ? 'paid' : ($paidOnInstallment > 0 ? 'partial' : 'unpaid');

                    $installment->update([
                        'paid_amount' => $paidOnInstallment,
                        'status' => $newInstStatus,
                    ]);
                }
            }

            // Recalculate parent invoice
            $payment->invoice->recalculateBalances();

            AuditLog::log('payment_voided', Payment::class, $payment->id, $oldValues, $payment->toArray());
        });

        return redirect()->back()->with('success', 'Pembayaran telah dibatalkan (void).');
    }
}

