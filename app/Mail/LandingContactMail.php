<?php

namespace App\Mail;

use App\Models\ExchangeOffice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LandingContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ExchangeOffice $exchangeOffice,
        public string $senderName,
        public string $senderEmail,
        public string $message,
        public ?string $senderPhone = null
    ) {}

    public function envelope(): Envelope
    {
        $subject = 'پیام تماس از سایت — ' . $this->exchangeOffice->name;
        return new Envelope(
            subject: $subject,
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.landing-contact',
        );
    }
}
