<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceDataController extends Controller
{
    public function index(Request $request): Response
    {
        $tab = $request->query('tab', 'invoices');
        $search = trim((string) $request->query('search', ''));
        $tenantId = $request->query('tenant_id', '');

        $invoicesQuery = Invoice::with(['tenant:id,name', 'client:id,name,company_name', 'project:id,name'])
            ->latest('invoice_date');
        $projectsQuery = Project::with(['tenant:id,name', 'client:id,name'])
            ->latest();
        $clientsQuery = Client::with(['tenant:id,name'])
            ->withCount('invoices')
            ->latest();

        if ($tenantId !== '') {
            $invoicesQuery->where('tenant_id', $tenantId);
            $projectsQuery->where('tenant_id', $tenantId);
            $clientsQuery->where('tenant_id', $tenantId);
        }

        if ($search !== '') {
            $invoicesQuery->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tenant', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
            $projectsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tenant', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
            $clientsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('tenant', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        return Inertia::render('Admin/WorkspaceData/Index', [
            'tab' => $tab,
            'filters' => [
                'search' => $search,
                'tenant_id' => $tenantId,
            ],
            'tenants' => Tenant::select('id', 'name')->orderBy('name')->get(),
            'counts' => [
                'invoices' => Invoice::count(),
                'projects' => Project::count(),
                'clients' => Client::count(),
            ],
            'invoices' => $invoicesQuery->paginate(15, ['*'], 'inv_page')->withQueryString(),
            'projects' => $projectsQuery->paginate(15, ['*'], 'proj_page')->withQueryString(),
            'clients' => $clientsQuery->paginate(15, ['*'], 'cli_page')->withQueryString(),
        ]);
    }
}

