<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly SubscriptionPayment $payment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bukti Pembayaran Upgrade Paket Baru — ' . ($this->payment->tenant?->name ?? 'Tenant'),
        );
    }

    public function content(): Content
    {
        $tenantName = e($this->payment->tenant?->name ?? '-');
        $userEmail = e($this->payment->user?->email ?? '-');
        $plan = e(strtoupper($this->payment->plan));
        $cycle = $this->payment->billing_cycle === 'annual' ? 'Tahunan' : 'Bulanan';
        $amount = 'Rp ' . number_format((float) $this->payment->amount, 0, ',', '.');
        $sender = e($this->payment->sender_name);
        $senderBank = e($this->payment->sender_bank ?: '-');
        $date = e($this->payment->transfer_date?->format('d M Y') ?? '-');
        $adminUrl = e(url('/admin/payments'));

        $html = <<<HTML
        <div style="font-family: 'Plus Jakarta Sans', Arial, sans-serif; max-width: 560px; margin: 0 auto; padding: 24px; background: #FAF8FF; border-radius: 16px; border: 1px solid #EDE9FE;">
          <div style="background: #6D28D9; color: #FFFFFF; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;">
            <strong style="font-size: 16px;">Konfirmasi Transfer Masuk &bull; InvoiceRapi</strong>
          </div>
          <p style="color: #0F172A; font-size: 14px;">Ada pengajuan upgrade paket dengan upload bukti transfer baru:</p>
          <table style="width: 100%; background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; padding: 12px; font-size: 13px; color: #334155; border-collapse: collapse;">
            <tr><td style="padding: 8px 12px; font-weight: 700;">Bisnis / Tenant</td><td style="padding: 8px 12px;">{$tenantName} ({$userEmail})</td></tr>
            <tr><td style="padding: 8px 12px; font-weight: 700;">Paket Dipilih</td><td style="padding: 8px 12px;">{$plan} ({$cycle})</td></tr>
            <tr><td style="padding: 8px 12px; font-weight: 700;">Nominal Transfer</td><td style="padding: 8px 12px; font-weight: 800; color: #6D28D9;">{$amount}</td></tr>
            <tr><td style="padding: 8px 12px; font-weight: 700;">Pengirim</td><td style="padding: 8px 12px;">{$sender} ({$senderBank})</td></tr>
            <tr><td style="padding: 8px 12px; font-weight: 700;">Tanggal Transfer</td><td style="padding: 8px 12px;">{$date}</td></tr>
          </table>
          <div style="margin-top: 20px; text-align: center;">
            <a href="{$adminUrl}" style="display: inline-block; background: #6D28D9; color: #FFFFFF; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 13px;">
              Verifikasi di Panel Admin
            </a>
          </div>
        </div>
        HTML;

        return new Content(htmlString: $html);
    }
}

