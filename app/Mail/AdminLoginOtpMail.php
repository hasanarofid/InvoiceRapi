<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminLoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $admin,
        public readonly string $otpCode,
        public readonly string $ipAddress = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Login Admin — Synbarra InvoiceRapi',
        );
    }

    public function content(): Content
    {
        $name = e($this->admin->name);
        $code = e($this->otpCode);
        $ip = e($this->ipAddress ?: 'Tidak diketahui');

        $html = <<<HTML
        <div style="font-family: 'Plus Jakarta Sans', Arial, sans-serif; max-width: 520px; margin: 0 auto; padding: 24px; background: #FAF8FF; border-radius: 16px; border: 1px solid #EDE9FE;">
          <div style="background: #6D28D9; color: #FFFFFF; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;">
            <strong style="font-size: 16px;">Synbarra InvoiceRapi &bull; Verifikasi Admin</strong>
          </div>
          <p style="color: #0F172A; font-size: 14px;">Halo <strong>{$name}</strong>,</p>
          <p style="color: #475569; font-size: 14px; line-height: 1.6;">
            Terdapat permintaan login ke Panel Admin Synbarra InvoiceRapi dari IP <strong>{$ip}</strong>. Masukkan kode verifikasi 6-digit berikut untuk melanjutkan:
          </p>
          <div style="background: #FFFFFF; border: 2px dashed #8B5CF6; border-radius: 12px; padding: 18px; text-align: center; margin: 20px 0;">
            <span style="font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #6D28D9; font-family: monospace;">{$code}</span>
          </div>
          <p style="color: #64748B; font-size: 12px; line-height: 1.5;">
            Kode ini berlaku selama <strong>10 menit</strong>. Jika Anda tidak merasa melakukan login ini, segera ubah password akun Anda.
          </p>
        </div>
        HTML;

        return new Content(htmlString: $html);
    }
}

