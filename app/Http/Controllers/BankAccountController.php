<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\TenantBankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;
        if (! $tenantId) {
            abort(403, 'Workspace belum aktif.');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:60',
            'account_holder' => 'required|string|max:150',
            'is_default' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($tenantId, $validated) {
            $hasAny = TenantBankAccount::where('tenant_id', $tenantId)->exists();
            $isDefault = (bool) ($validated['is_default'] ?? ! $hasAny);

            if ($isDefault) {
                TenantBankAccount::where('tenant_id', $tenantId)->update(['is_default' => false]);
            }

            $account = TenantBankAccount::create([
                'tenant_id' => $tenantId,
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'account_holder' => $validated['account_holder'],
                'is_default' => $isDefault,
            ]);

            AuditLog::log('bank_account_created', TenantBankAccount::class, $account->id, null, $account->toArray());
        });

        return redirect()->back()->with('success', 'Rekening pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, TenantBankAccount $bankAccount): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;
        if ($bankAccount->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:60',
            'account_holder' => 'required|string|max:150',
            'is_default' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($tenantId, $bankAccount, $validated) {
            $oldValues = $bankAccount->toArray();
            $isDefault = (bool) ($validated['is_default'] ?? $bankAccount->is_default);

            if ($isDefault) {
                TenantBankAccount::where('tenant_id', $tenantId)
                    ->where('id', '!=', $bankAccount->id)
                    ->update(['is_default' => false]);
            }

            $bankAccount->update([
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'account_holder' => $validated['account_holder'],
                'is_default' => $isDefault,
            ]);

            AuditLog::log('bank_account_updated', TenantBankAccount::class, $bankAccount->id, $oldValues, $bankAccount->toArray());
        });

        return redirect()->back()->with('success', 'Rekening pembayaran berhasil diperbarui.');
    }

    public function setDefault(TenantBankAccount $bankAccount): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;
        if ($bankAccount->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        DB::transaction(function () use ($tenantId, $bankAccount) {
            TenantBankAccount::where('tenant_id', $tenantId)->update(['is_default' => false]);
            $bankAccount->update(['is_default' => true]);
        });

        return redirect()->back()->with('success', 'Rekening utama berhasil diperbarui.');
    }

    public function destroy(TenantBankAccount $bankAccount): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;
        if ($bankAccount->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $oldValues = $bankAccount->toArray();
        $wasDefault = $bankAccount->is_default;
        $bankAccount->delete();

        if ($wasDefault) {
            $next = TenantBankAccount::where('tenant_id', $tenantId)->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        AuditLog::log('bank_account_deleted', TenantBankAccount::class, $bankAccount->id, $oldValues, null);

        return redirect()->back()->with('success', 'Rekening pembayaran berhasil dihapus.');
    }
}

