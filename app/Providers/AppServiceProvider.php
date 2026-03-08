<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Schema::hasTable('settings')) {
            $settings = \App\Models\Setting::all()->pluck('value', 'key');
            
            if ($settings->get('mail_host')) {
                config([
                    'mail.mailers.smtp.host' => $settings->get('mail_host'),
                    'mail.mailers.smtp.port' => $settings->get('mail_port'),
                    'mail.mailers.smtp.username' => $settings->get('mail_username'),
                    'mail.mailers.smtp.password' => $settings->get('mail_password'),
                    'mail.mailers.smtp.encryption' => $settings->get('mail_encryption') === 'null' ? null : $settings->get('mail_encryption'),
                    'mail.from.address' => $settings->get('mail_from_address'),
                    'mail.from.name' => $settings->get('mail_from_name'),
                ]);
            }
        }
    }
}
