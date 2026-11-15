<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di Synbarra InvoiceRapi!',
        );
    }

    public function content(): Content
    {
        $name = e($this->user->name);
        $dashboardUrl = e(url('/dashboard'));

        $html = <<<HTML
        <div style="font-family: 'Plus Jakarta Sans', Arial, sans-serif; max-width: 540px; margin: 0 auto; padding: 24px; background: #FAF8FF; border-radius: 16px; border: 1px solid #EDE9FE;">
          <div style="background: #6D28D9; color: #FFFFFF; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;">
            <strong style="font-size: 16px;">Synbarra InvoiceRapi</strong>
          </div>
          <p style="color: #0F172A; font-size: 14px;">Halo <strong>{$name}</strong>,</p>
          <p style="color: #475569; font-size: 14px; line-height: 1.6;">
            Terima kasih telah mendaftar di <strong>Synbarra InvoiceRapi</strong>. Anda kini dapat mengelola invoice, jadwal DP &amp; termin pembayaran, serta memantau piutang klien dalam satu aplikasi.
          </p>
          <div style="margin-top: 20px; text-align: center;">
            <a href="{$dashboardUrl}" style="display: inline-block; background: #6D28D9; color: #FFFFFF; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 13px;">
              Buka Dashboard InvoiceRapi
            </a>
          </div>
          <p style="color: #94A3B8; font-size: 11px; margin-top: 24px;">
            Email ini dikirim otomatis oleh sistem notifikasi Synbarra InvoiceRapi (synbarr.techno@gmail.com).
          </p>
        </div>
        HTML;

        return new Content(htmlString: $html);
    }
}

