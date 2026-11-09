<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id;
        $search = $request->query('search', '');
        $status = $request->query('status', 'active'); // active, archived, all

        $query = Client::where('tenant_id', $tenantId)
            ->withCount(['projects', 'invoices'])
            ->latest();

        if ($status === 'active') {
            $query->where('is_archived', false);
        } elseif ($status === 'archived') {
            $query->where('is_archived', true);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(15)->withQueryString();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $tenantId = auth()->user()->tenant_id;

        $client = Client::create(array_merge($validated, [
            'tenant_id' => $tenantId,
            'is_archived' => false,
        ]));

        AuditLog::log('client_created', Client::class, $client->id, null, $client->toArray());

        return redirect()->back()->with('success', 'Data klien berhasil ditambahkan.');
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        // Enforce tenant boundary
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'is_archived' => 'nullable|boolean',
        ]);

        $oldValues = $client->toArray();
        $client->update($validated);

        AuditLog::log('client_updated', Client::class, $client->id, $oldValues, $client->toArray());

        return redirect()->back()->with('success', 'Data klien berhasil diperbarui.');
    }

    /**
     * Toggle archive status for client.
     */
    public function toggleArchive(Client $client): RedirectResponse
    {
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $client->update([
            'is_archived' => ! $client->is_archived,
        ]);

        $statusMsg = $client->is_archived ? 'diarsipkan' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Klien berhasil {$statusMsg}.");
    }
}

