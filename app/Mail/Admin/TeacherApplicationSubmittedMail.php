<?php

namespace App\Mail\Admin;

use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Staff $application,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Teacher Application - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.teacher-application-submitted',
            text: 'emails.admin.teacher-application-submitted-text',
            with: [
                'application' => $this->application,
            ],
        );
    }
}
