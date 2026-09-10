<?php

namespace App\Support;

use App\Models\Setting;

class AdminNotificationRecipients
{
    /**
     * Resolve admin notification recipients.
     *
     * @return array{recipients: array<int, string>, source: 'settings'|'none'}
     */
    public static function resolve(?string $channel = null): array
    {
        $fromSettings = self::fromSettings($channel);
        if (! empty($fromSettings)) {
            return [
                'recipients' => $fromSettings,
                'source' => 'settings',
            ];
        }

        return [
            'recipients' => [],
            'source' => 'none',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function fromSettings(?string $channel = null): array
    {
        try {
            $key = match ($channel) {
                'contact' => 'contact_notification_emails',
                default => 'admin_notification_emails',
            };

            $raw = (string) Setting::getValue($key, '');

            if ($raw === '' && $key !== 'admin_notification_emails') {
                $raw = (string) Setting::getValue('admin_notification_emails', '');
            }
        } catch (\Throwable) {
            return [];
        }

        return self::parseEmails($raw);
    }

    /**
     * @return array<int, string>
     */
    private static function parseEmails(string $raw): array
    {
        if ($raw === '') {
            return [];
        }

        $parts = preg_split('/[,\n;\x{060C}]+/u', $raw) ?: [];

        $emails = [];
        foreach ($parts as $part) {
            $email = trim((string) $part);
            if ($email === '') {
                continue;
            }

            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                continue;
            }

            $emails[] = $email;
        }

        return array_values(array_unique($emails));
    }
}
