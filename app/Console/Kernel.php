<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // ✅ Tambahkan ini di dalam class
    protected $commands = [
        \App\Console\Commands\GenerateDailyReport::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Menjalankan command setiap hari pukul 23.00
        $schedule->command('report:daily')->dailyAt('23:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
