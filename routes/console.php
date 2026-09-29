<?php

use App\Console\Commands\SendBellscaleReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command('queue:work --stop-when-empty --tries=3')
    ->everyMinute()
    ->withoutOverlapping();
Schedule::command(SendBellscaleReminders::class)->everyMinute();
//->dailyAt('08:00');


Schedule::command('app:dispatch-reoccurence-survey-jobs')
    ->daily()
    ->withoutOverlapping()
    ->runInBackground();
