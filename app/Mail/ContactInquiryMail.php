<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $inquiry;

    /**
     * Create a new message instance.
     */
    public function __construct(array $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $service = $this->inquiry['serviceRequired'] ?? 'Konsultasi Sistem';
        $senderName = $this->inquiry['fullName'] ?? 'Calon Klien';
        $senderEmail = $this->inquiry['email'] ?? 'no-reply@synbarra.com';

        return new Envelope(
            subject: "[Synbarra Lead] {$service} — {$senderName}",
            replyTo: [
                new Address($senderEmail, $senderName),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-inquiry',
            with: [
                'data' => $this->inquiry,
            ],
        );
    }
}
