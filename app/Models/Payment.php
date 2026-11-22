<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'payment_installment_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'proof_path',
        'notes',
        'is_void',
        'void_reason',
        'voided_at',
        'voided_by',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::created(function (Payment $payment) {
            if (empty($payment->kode_transaksi)) {
                $date = ($payment->created_at ?? now())->format('Ymd');
                $payment->kode_transaksi = 'TRX-PAY-' . $date . '-' . str_pad((string) $payment->id, 5, '0', STR_PAD_LEFT);
                $payment->saveQuietly();
            }
        });

        static::updating(function (Payment $payment) {
            $original = $payment->getOriginal('kode_transaksi');
            if (! empty($original)) {
                $payment->kode_transaksi = $original;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'is_void' => 'boolean',
            'voided_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(PaymentInstallment::class, 'payment_installment_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function voider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}

