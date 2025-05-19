<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WarningEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $messageBody;

    public function __construct($user, $messageBody = null)
    {
        $this->user = $user;
        $this->messageBody = $messageBody ?? 'This is a warning due to policy violations. Please comply with our community standards.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Warning Notice from Admin',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.warning',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
