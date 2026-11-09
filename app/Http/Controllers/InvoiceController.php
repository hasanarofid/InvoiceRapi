<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentInstallment;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices for the active tenant.
     */
    public function index(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id;
        $search = $request->query('search', '');
        $status = $request->query('status', 'all'); // all, draft, issued, partial, paid, overdue

        $query = Invoice::where('tenant_id', $tenantId)
            ->with(['client', 'project', 'installments'])
            ->latest('invoice_date');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        // 4 Summary Metrics
        $totalInvoiced = (float) Invoice::where('tenant_id', $tenantId)->whereNotIn('status', ['draft', 'cancelled'])->sum('total');
        $totalPaid = (float) Invoice::where('tenant_id', $tenantId)->whereNotIn('status', ['draft', 'cancelled'])->sum('total_paid');
        $totalOutstanding = (float) Invoice::where('tenant_id', $tenantId)->whereNotIn('status', ['draft', 'cancelled'])->sum('balance_due');
        $totalOverdue = (float) Invoice::where('tenant_id', $tenantId)->where('status', 'overdue')->sum('balance_due');

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
            'metrics' => [
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'total_outstanding' => $totalOutstanding,
                'total_overdue' => $totalOverdue,
            ],
        ]);
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(): Response
    {
        $tenantId = auth()->user()->tenant_id;

        $clients = Client::where('tenant_id', $tenantId)
            ->where('is_archived', false)
            ->select('id', 'name', 'company_name', 'phone', 'email')
            ->orderBy('name')
            ->get();

        $projects = Project::where('tenant_id', $tenantId)
            ->where('status', '!=', 'cancelled')
            ->select('id', 'client_id', 'name', 'code', 'contract_amount')
            ->orderBy('name')
            ->get();

        // Generate next suggested invoice number
        $yearMonth = date('Ym');
        $lastNumber = Invoice::where('tenant_id', $tenantId)
            ->where('invoice_number', 'like', "INV/{$yearMonth}/%")
            ->count() + 1;
        $suggestedNumber = sprintf("INV/%s/%04d", $yearMonth, $lastNumber);

        return Inertia::render('Invoices/Create', [
            'clients' => $clients,
            'projects' => $projects,
            'suggestedNumber' => $suggestedNumber,
        ]);
    }

    /**
     * Store a newly created invoice with line items and installments.
     */
    public function store(Request $request): RedirectResponse
    {
        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_number' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',

            // Line Items
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',

            // Installments (Termin)
            'installments' => 'required|array|min:1',
            'installments.*.name' => 'required|string|max:100',
            'installments.*.percentage' => 'nullable|numeric|min:0|max:100',
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.due_date' => 'required|date',
        ]);

        // Verify client & project belongs to tenant
        Client::where('tenant_id', $tenantId)->findOrFail($validated['client_id']);
        if (! empty($validated['project_id'])) {
            Project::where('tenant_id', $tenantId)->findOrFail($validated['project_id']);
        }

        // Prevent duplicate invoice number in same workspace
        if (Invoice::where('tenant_id', $tenantId)->where('invoice_number', $validated['invoice_number'])->exists()) {
            return redirect()->back()->withErrors(['invoice_number' => 'Nomor invoice ini sudah digunakan.'])->withInput();
        }

        DB::transaction(function () use ($tenantId, $validated, &$invoice) {
            // 1. Calculate items subtotal
            $subtotal = 0.00;
            foreach ($validated['items'] as $item) {
                $itemTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);
                $subtotal += max(0, $itemTotal);
            }

            $discount = (float) ($validated['discount'] ?? 0);
            $tax = (float) ($validated['tax'] ?? 0);
            $grandTotal = max(0, $subtotal - $discount + $tax);

            // 2. Create Invoice
            $invoice = Invoice::create([
                'tenant_id' => $tenantId,
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'] ?? null,
                'invoice_number' => $validated['invoice_number'],
                'public_token' => Str::random(40),
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'description' => $validated['description'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $grandTotal,
                'total_paid' => 0.00,
                'balance_due' => $grandTotal,
                'notes' => $validated['notes'] ?? null,
                'payment_instructions' => $validated['payment_instructions'] ?? null,
                'status' => 'issued',
                'issued_at' => now(),
            ]);

            // 3. Save line items
            foreach ($validated['items'] as $idx => $item) {
                $itemTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0.00,
                    'total_price' => max(0, $itemTotal),
                    'sort_order' => $idx + 1,
                ]);
            }

            // 4. Save installments (Termin)
            foreach ($validated['installments'] as $idx => $inst) {
                PaymentInstallment::create([
                    'invoice_id' => $invoice->id,
                    'name' => $inst['name'],
                    'percentage' => $inst['percentage'] ?? null,
                    'amount' => $inst['amount'],
                    'paid_amount' => 0.00,
                    'due_date' => $inst['due_date'],
                    'status' => 'unpaid',
                    'sort_order' => $idx + 1,
                ]);
            }

            AuditLog::log('invoice_created', Invoice::class, $invoice->id, null, $invoice->toArray());
        });

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice dan jadwal termin pembayaran berhasil diterbitkan.');
    }

    /**
     * Display detailed invoice, line items, installments, and payment history.
     */
    public function show(Invoice $invoice): Response
    {
        $tenantId = auth()->user()->tenant_id;
        if ($invoice->tenant_id !== $tenantId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $invoice->load([
            'client',
            'project',
            'items',
            'installments.payments',
            'payments.creator',
            'tenant',
        ]);

        return Inertia::render('Invoices/Show', [
            'invoice' => $invoice,
            'publicUrl' => route('invoices.public', $invoice->public_token),
        ]);
    }
}

