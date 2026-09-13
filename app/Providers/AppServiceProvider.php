<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->applyMailSettingsFromDatabase();
    }

    private function applyMailSettingsFromDatabase(): void
    {
        try {
            $host = Setting::getValue('mail_host');

            // Only override if the dashboard has been configured
            if (empty($host)) {
                return;
            }

            $port       = (int) Setting::getValue('mail_port', 587);
            $username   = Setting::getValue('mail_username', '');
            $password   = Setting::getValue('mail_password', '');
            $encryption = Setting::getValue('mail_encryption', 'tls');
            $fromAddress = Setting::getValue('mail_from_address', '');
            $fromName    = Setting::getValue('mail_from_name', config('app.name'));

            config([
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => $port,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.mailers.smtp.scheme'     => $encryption === 'ssl' ? 'ssl' : ($encryption === 'tls' ? 'tls' : null),
                'mail.mailers.smtp.transport'  => 'smtp',
                'mail.default'                 => 'smtp',
                'mail.from.address'            => $fromAddress ?: $username,
                'mail.from.name'               => $fromName,
            ]);
        } catch (\Throwable) {
            // DB not ready yet (e.g. during migrations) — fall back to .env values
        }
    }
}
