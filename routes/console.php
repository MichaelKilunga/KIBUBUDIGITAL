<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$reportTime = '08:00';
try {
    if (\Schema::hasTable('settings')) {
        $reportTime = \App\Models\Setting::where('key', 'report_schedule')->value('value') ?? '08:00';
    }
} catch (\Exception $e) {}

\Illuminate\Support\Facades\Schedule::command('app:send-daily-report')->dailyAt($reportTime);
