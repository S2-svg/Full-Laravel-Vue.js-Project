<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled Discount Processing ──
// Run every hour to check for newly activated or expired discounts.
// Add this to your server's crontab:
// * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('products:process-discounts')->hourly();
