<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ============================================================
        // ACTIVITY LOG MAINTENANCE
        // ============================================================

        /**
         * ✅ Prune activity logs older than 90 days.
         * Runs daily at 2:00 AM.
         *
         * Manual run:
         *   php artisan activity:prune --days=90
         */
        $schedule->command('activity:prune --days=90')
            ->daily()
            ->at('02:00')
            ->name('Prune activity logs')
            ->onOneServer()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        /**
         * ✅ Archive activity logs older than 6 months (optional).
         * Runs every Sunday at 3:00 AM.
         *
         * If you don't want archiving, delete this block.
         * Also requires the `activity_logs_archive` table to exist.
         *
         * Manual run:
         *   php artisan activity:archive --months=6
         */
        $schedule->command('activity:archive --months=6')
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->name('Archive old activity logs')
            ->onOneServer()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        // ============================================================
        // OPTIONAL: Clear Laravel caches nightly
        // ============================================================
        // $schedule->command('cache:prune-stale-tags')->hourly();
        // $schedule->command('queue:prune-failed --hours=48')->daily();
        // $schedule->command('session:prune')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
