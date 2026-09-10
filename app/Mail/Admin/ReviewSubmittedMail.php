<?php

namespace App\Mail\Admin;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Review $review,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Student Review - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.review-submitted',
            text: 'emails.admin.review-submitted-text',
            with: [
                'review' => $this->review,
            ],
        );
    }
}
