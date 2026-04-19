<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginMail extends Mailable
{
    use Queueable, SerializesModels;

   // -----------------------------------------------
    // Step 1 — Declare public property
    // -----------------------------------------------
    public array $params;


    /**
     * Create a new message instance.
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject:  $this->subject ?? 'New Login Detected '. config('app.name'),

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.login',
            with: [
                'params' => $this->params,
                // Access in blade as $params
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
