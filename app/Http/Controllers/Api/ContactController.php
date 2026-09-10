<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Http\Requests\Api\ContactStoreRequest;
use App\Mail\Admin\ContactSubmittedMail;
use App\Support\AdminNotificationRecipients;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends ApiController
{
    public function __invoke(ContactStoreRequest $request): JsonResponse
    {
        $payload = Contact::create($request->validated());

        $resolvedRecipients = AdminNotificationRecipients::resolve('contact');
        $recipients = $resolvedRecipients['recipients'];
        if (! empty($recipients)) {
            Log::info('admin_notification.contact.preparing', [
                'contact_id' => $payload->id,
                'to' => $recipients,
                'recipient_source' => $resolvedRecipients['source'],
                'mailer' => config('mail.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_scheme' => config('mail.mailers.smtp.scheme'),
                'mail_from' => config('mail.from.address'),
            ]);
            try {
                Mail::to($recipients)->send(new ContactSubmittedMail($payload));
                Log::info('admin_notification.contact.sent', [
                    'contact_id' => $payload->id,
                    'to' => $recipients,
                ]);
            } catch (\Throwable $e) {
                Log::error('admin_notification.contact.failed', [
                    'contact_id' => $payload->id,
                    'to' => $recipients,
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                ]);
                report($e);
            }
        } else {
            Log::warning('admin_notification.contact.skipped_no_recipients', [
                'contact_id' => $payload->id,
                'recipient_source' => $resolvedRecipients['source'],
                'config_cached' => app()->configurationIsCached(),
                'notifications_config_present' => config()->has('notifications.admin_emails'),
            ]);
        }

        return response()->json([
            'message' => 'Contact request received.',
            'data' => $payload,
        ], 201);
    }
}
