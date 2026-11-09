<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'project_id',
        'invoice_number',
        'public_token',
        'invoice_date',
        'due_date',
        'description',
        'subtotal',
        'discount',
        'tax',
        'total',
        'total_paid',
        'balance_due',
        'notes',
        'payment_instructions',
        'status',
        'issued_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'total_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'issued_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->public_token)) {
                $invoice->public_token = Str::random(40);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class)->orderBy('sort_order');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->where('is_void', false)->latest('payment_date');
    }

    /**
     * Recalculate invoice totals and payment balances.
     */
    public function recalculateBalances(): void
    {
        $totalPaid = $this->payments()->sum('amount');
        $balanceDue = max(0, (float) $this->total - (float) $totalPaid);

        $newStatus = $this->status;
        if ($this->status !== 'draft' && $this->status !== 'cancelled') {
            if ($balanceDue <= 0.00) {
                $newStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $newStatus = 'partial';
            } elseif (now()->isAfter($this->due_date)) {
                $newStatus = 'overdue';
            } else {
                $newStatus = 'issued';
            }
        }

        $this->updateQuietly([
            'total_paid' => $totalPaid,
            'balance_due' => $balanceDue,
            'status' => $newStatus,
        ]);
    }
}

