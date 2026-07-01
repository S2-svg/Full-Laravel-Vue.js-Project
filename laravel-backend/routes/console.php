<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled Discount Processing ──
// Run every hour to check for newly activated or expired discounts.
Schedule::command('products:process-discounts')->hourly();

// ── Monthly VAT Report Generation ──
// Runs on the 1st of every month at 8:00 AM, generating the previous month's VAT declaration
// and emailing it to the configured recipient (TAX_REPORT_EMAIL in .env).
//
// To test or manually run: php artisan tax:generate-monthly-report
// To send to a specific email: php artisan tax:generate-monthly-report --email=finance@ministry.gov.kh
// For a dry run (generates PDF only): php artisan tax:generate-monthly-report --keep-pdf
//
// Ensure your server's crontab is set:
// * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('tax:generate-monthly-report')
    ->monthlyOn(1, '08:00')
    ->timezone('Asia/Phnom_Penh')
    ->appendOutputTo(storage_path('logs/tax-report-schedule.log'));
