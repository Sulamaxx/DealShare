<?php

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

if (!function_exists('send_generic_email')) {
    /**
     * Sends a generic email with a dynamic subject and body.
     *
     * @param string $toEmail The recipient's email address.
     * @param string $subject The subject line of the email.
     * @param string $bodyContent The HTML or Markdown content for the email body.
     * @return bool True if the email was dispatched, false otherwise.
     */
    function send_generic_email(string $toEmail, string $subject, string $bodyContent, ?string $buttonUrl = null, ?string $buttonText = null): bool
    {
        try {
            // Dispatch the Mailable to the queue for sending
            Mail::to($toEmail)->queue(new GenericMail($subject, $bodyContent, $buttonUrl, $buttonText));
            Log::info("Generic email dispatched to {$toEmail} with subject: {$subject}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send generic email to {$toEmail}. Subject: {$subject}. Error: " . $e->getMessage());
            return false;
        }
    }
}
