<?php

use App\Console\Commands\MonitorQueueSizeCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule every minute
Schedule::command(MonitorQueueSizeCommand::class)->everyMinute();
