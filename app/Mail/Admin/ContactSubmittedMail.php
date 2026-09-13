<?php

namespace App\Mail\Admin;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Enquiry $contact,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Enquiry - ' . config('app.name'),
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
