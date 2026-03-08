<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@kibubu.com'],
            [
                'name' => 'Kibubu Admin',
                'password' => \Hash::make('admin123'),
            ]
        );

        // Default Branding Settings
        $settings = [
            'primary_color' => '#D4AF37',
            'secondary_color' => '#FFD700',
            'charity_red' => '#B22222',
            'admin_email' => 'admin@kibubu.com',
            'report_schedule' => '08:00', // 8 AM daily
            
            // SMTP Stubs
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => config('mail.mailers.smtp.password'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
