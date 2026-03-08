<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a daily report of payment intents to the admin email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = \App\Models\Setting::where('key', 'admin_email')->value('value');
        
        if (!$adminEmail) {
            $this->error('Admin email not configured in settings.');
            return;
        }

        $yesterday = now()->subDay();
        $totalClicks = \App\Models\PaymentIntent::where('created_at', '>=', $yesterday)->count();
        $stats = \App\Models\PaymentIntent::select('provider_name', \DB::raw('count(*) as total'))
            ->where('created_at', '>=', $yesterday)
            ->groupBy('provider_name')
            ->get();

        \Mail::to($adminEmail)->send(new \App\Mail\DailyReportMail($stats, $totalClicks));

        $this->info('Daily report sent to ' . $adminEmail);
    }
}
