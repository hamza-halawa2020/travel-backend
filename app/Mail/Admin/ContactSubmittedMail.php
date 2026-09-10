<?php

namespace App\Mail\Admin;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Contact $contact,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Message - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.contact-submitted',
            text: 'emails.admin.contact-submitted-text',
            with: [
                'contact' => $this->contact,
            ],
        );
    }
}
