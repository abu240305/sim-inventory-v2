<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan backup database setiap hari jam 00:00 (tengah malam)
Schedule::command('backup:run --only-db')->dailyAt('00:00');

// Bersihkan file backup lama setiap hari jam 01:00 pagi
Schedule::command('backup:clean')->dailyAt('01:00');
