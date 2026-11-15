<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly SubscriptionPayment $payment,
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = $this->payment->status === 'approved' ? 'Disetujui & Aktif' : 'Ditolak';

        return new Envelope(
            subject: "Status Pembayaran Paket InvoiceRapi: {$statusLabel}",
        );
    }

    public function content(): Content
    {
        $name = e($this->payment->user?->name ?? 'Pelanggan');
        $plan = e(strtoupper($this->payment->plan));
        $isApproved = $this->payment->status === 'approved';
        $badgeColor = $isApproved ? '#22C55E' : '#EF4444';
        $statusText = $isApproved ? 'DISETUJUI' : 'DITOLAK';
        $notes = e($this->payment->admin_notes ?: ($isApproved ? 'Paket Anda telah aktif dan siap digunakan.' : 'Mohon periksa kembali bukti transfer dan unggah ulang.'));
        $billingUrl = e(url('/billing'));

        $html = <<<HTML
        <div style="font-family: 'Plus Jakarta Sans', Arial, sans-serif; max-width: 540px; margin: 0 auto; padding: 24px; background: #FAF8FF; border-radius: 16px; border: 1px solid #EDE9FE;">
          <div style="background: #6D28D9; color: #FFFFFF; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;">
            <strong style="font-size: 16px;">Synbarra InvoiceRapi &bull; Status Langganan</strong>
          </div>
          <p style="color: #0F172A; font-size: 14px;">Halo <strong>{$name}</strong>,</p>
          <p style="color: #475569; font-size: 14px; line-height: 1.6;">
            Pengajuan pembayaran untuk <strong>Paket {$plan}</strong> telah diperiksa oleh tim kami dengan status:
          </p>
          <div style="margin: 16px 0; text-align: center;">
            <span style="display: inline-block; background: {$badgeColor}; color: #FFFFFF; padding: 8px 18px; border-radius: 999px; font-weight: 800; font-size: 13px;">
              {$statusText}
            </span>
          </div>
          <p style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px; color: #334155; font-size: 13px;">
            <strong>Catatan Admin:</strong> {$notes}
          </p>
          <div style="margin-top: 20px; text-align: center;">
            <a href="{$billingUrl}" style="display: inline-block; background: #6D28D9; color: #FFFFFF; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 13px;">
              Lihat Status Paket Saya
            </a>
          </div>
        </div>
        HTML;

        return new Content(htmlString: $html);
    }
}

