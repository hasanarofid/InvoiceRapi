<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ServiceItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceItemController extends Controller
{
    public function index(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id;
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status', 'active');

        $query = ServiceItem::where('tenant_id', $tenantId)->latest();

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Items/Index', [
            'items' => $query->paginate(20)->withQueryString(),
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $item = ServiceItem::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $validated['name'],
            'unit' => $validated['unit'] ?? null,
            'unit_price' => $validated['unit_price'],
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        AuditLog::log('service_item_created', ServiceItem::class, $item->id, null, $item->toArray());

        return redirect()->back()->with('success', 'Master jasa/barang berhasil ditambahkan.');
    }

    public function update(Request $request, ServiceItem $item): RedirectResponse
    {
        if ($item->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $oldValues = $item->toArray();
        $item->update([
            'name' => $validated['name'],
            'unit' => $validated['unit'] ?? null,
            'unit_price' => $validated['unit_price'],
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? $item->is_active),
        ]);

        AuditLog::log('service_item_updated', ServiceItem::class, $item->id, $oldValues, $item->toArray());

        return redirect()->back()->with('success', 'Master jasa/barang berhasil diperbarui.');
    }

    public function destroy(ServiceItem $item): RedirectResponse
    {
        $user = auth()->user();
        if ($item->tenant_id !== $user->tenant_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (! $user->canDeleteRecords()) {
            abort(403, 'Role Staff tidak diizinkan menghapus data.');
        }

        $oldValues = $item->toArray();
        $item->delete();

        AuditLog::log('service_item_deleted', ServiceItem::class, $item->id, $oldValues, null);

        return redirect()->back()->with('success', 'Master jasa/barang berhasil dihapus.');
    }
}
