<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $bodyContent;
    public $buttonUrl;   // New property for dynamic URL
    public $buttonText;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subjectLine, string $bodyContent, ?string $buttonUrl = null, ?string $buttonText = null)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyContent = $bodyContent;
        $this->buttonUrl = $buttonUrl;     // Assign to new property
        $this->buttonText = $buttonText;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.generic',
            with: [
                'bodyContent' => $this->bodyContent, // Pass the dynamic body content to the view
                'buttonUrl' => $this->buttonUrl,   // Pass to view
                'buttonText' => $this->buttonText,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
