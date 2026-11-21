<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Services\PlanLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanSettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Plans/Index', [
            'plans' => PlanLimitService::getPlans(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plans' => 'required|array',
            'plans.*.name' => 'required|string|max:50',
            'plans.*.monthly_price' => 'required|numeric|min:0',
            'plans.*.annual_price' => 'required|numeric|min:0',
            'plans.*.max_invoices_per_month' => 'nullable|integer|min:1',
            'plans.*.max_clients' => 'nullable|integer|min:1',
            'plans.*.max_users' => 'required|integer|min:1',
            'plans.*.features' => 'required|array',
            'plans.*.features.dp_termin' => 'required|boolean',
            'plans.*.features.auto_reminder' => 'required|boolean',
            'plans.*.features.receivables_report' => 'required|boolean',
            'plans.*.features.custom_public_link' => 'required|boolean',
            'plans.*.features.csv_export_audit' => 'required|boolean',
        ]);

        $sanitized = [];
        foreach (['gratis', 'pro', 'bisnis'] as $slug) {
            if (! isset($validated['plans'][$slug])) {
                continue;
            }
            $p = $validated['plans'][$slug];
            $sanitized[$slug] = [
                'name' => $p['name'],
                'monthly_price' => (int) $p['monthly_price'],
                'annual_price' => (int) $p['annual_price'],
                'max_invoices_per_month' => $p['max_invoices_per_month'] !== null && $p['max_invoices_per_month'] !== ''
                    ? (int) $p['max_invoices_per_month']
                    : null,
                'max_clients' => $p['max_clients'] !== null && $p['max_clients'] !== ''
                    ? (int) $p['max_clients']
                    : null,
                'max_users' => (int) $p['max_users'],
                'features' => [
                    'dp_termin' => (bool) $p['features']['dp_termin'],
                    'auto_reminder' => (bool) $p['features']['auto_reminder'],
                    'receivables_report' => (bool) $p['features']['receivables_report'],
                    'custom_public_link' => (bool) $p['features']['custom_public_link'],
                    'csv_export_audit' => (bool) $p['features']['csv_export_audit'],
                ],
            ];
        }

        $old = PlanLimitService::getPlans();
        Setting::setValue('invoicerapi_plans', json_encode($sanitized), 'json');

        AuditLog::log('plans_updated', Setting::class, 0, $old, $sanitized);

        return redirect()->back()->with('success', 'Pengaturan harga, kuota, dan fitur paket berhasil diperbarui.');
    }
}

