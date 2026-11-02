<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeadController extends Controller
{
    /**
     * Display a listing of leads & inquiries.
     */
    public function index(Request $request): Response
    {
        $query = Lead::orderBy('created_at', 'desc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                    ->orWhere('company_name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('whatsapp_number', 'like', "%{$s}%")
                    ->orWhere('service_required', 'like', "%{$s}%");
            });
        }

        $leads = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'proposal' => Lead::where('status', 'proposal')->count(),
            'won' => Lead::where('status', 'won')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
        ];

        return Inertia::render('Admin/Leads/Index', [
            'leads' => $leads,
            'statusCounts' => $statusCounts,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    /**
     * Store a newly created lead manually (e.g. from direct outreach).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:120',
            'company_name' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150',
            'whatsapp_number' => 'required|string|max:30',
            'service_required' => 'required|string|max:100',
            'estimated_budget' => 'nullable|string|max:100',
            'project_description' => 'nullable|string|max:2000',
            'source' => 'required|string|max:50',
            'status' => 'required|in:new,contacted,qualified,proposal,won,lost',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Lead::create($validated);

        return back()->with('success', 'Prospek baru berhasil ditambahkan.');
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,proposal,won,lost',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'company_name' => 'nullable|string|max:150',
            'estimated_budget' => 'nullable|string|max:100',
        ]);

        $lead->update($validated);

        return back()->with('success', "Status prospek {$lead->full_name} berhasil diperbarui.");
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead): RedirectResponse
    {
        $name = $lead->full_name;
        $lead->delete();

        return back()->with('success', "Prospek {$name} berhasil dihapus.");
    }
}
