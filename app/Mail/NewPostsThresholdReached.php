<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPostsThresholdReached extends Mailable
{
    use Queueable, SerializesModels;

    public $postsCount;
    public $threshold;

    /**
     * Create a new message instance.
     */
    public function __construct(int $postsCount, int $threshold)
    {
        $this->postsCount = $postsCount;
        $this->threshold = $threshold;
    }

    public function build()
    {
        return $this->subject('New Posts Milestone Reached on ' . config('app.name'))
            ->markdown('emails.admin.new_posts_notification'); // Create this Blade view in the next step
    }

    /**
     * Get the message envelope.
     */
    /* public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Posts Threshold Reached',
        );
    } */

    /**
     * Get the message content definition.
     */
    /* public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    } */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    /* public function attachments(): array
    {
        return [];
    } */
}
