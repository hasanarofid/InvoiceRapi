<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'plan',
        'billing_cycle',
        'amount',
        'bank_name',
        'account_number',
        'account_holder',
        'sender_name',
        'sender_bank',
        'transfer_date',
        'proof_path',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected static function booted(): void
    {
        static::created(function (SubscriptionPayment $payment) {
            if (empty($payment->kode_transaksi)) {
                $date = ($payment->created_at ?? now())->format('Ymd');
                $payment->kode_transaksi = 'TRX-SUB-' . $date . '-' . str_pad((string) $payment->id, 5, '0', STR_PAD_LEFT);
                $payment->saveQuietly();
            }
        });

        static::updating(function (SubscriptionPayment $payment) {
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
            'transfer_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

