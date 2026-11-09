<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id;
        $search = $request->query('search', '');
        $status = $request->query('status', 'all'); // all, active, completed, draft, cancelled
        $clientId = $request->query('client_id');

        $query = Project::where('tenant_id', $tenantId)
            ->with(['client'])
            ->withCount('invoices')
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (! empty($clientId)) {
            $query->where('client_id', $clientId);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $projects = $query->paginate(15)->withQueryString();

        $clients = Client::where('tenant_id', $tenantId)
            ->where('is_archived', false)
            ->select('id', 'name', 'company_name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'clients' => $clients,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'client_id' => $clientId,
            ],
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'contract_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        // Verify client belongs to current tenant
        $client = Client::where('tenant_id', $tenantId)->findOrFail($validated['client_id']);

        $project = Project::create(array_merge($validated, [
            'tenant_id' => $tenantId,
            'is_archived' => false,
        ]));

        AuditLog::log('project_created', Project::class, $project->id, null, $project->toArray());

        return redirect()->back()->with('success', 'Data proyek berhasil dibuat.');
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;

        if ($project->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'contract_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        // Verify client belongs to tenant
        Client::where('tenant_id', $tenantId)->findOrFail($validated['client_id']);

        $oldValues = $project->toArray();
        $project->update($validated);

        AuditLog::log('project_updated', Project::class, $project->id, $oldValues, $project->toArray());

        return redirect()->back()->with('success', 'Data proyek berhasil diperbarui.');
    }
}

