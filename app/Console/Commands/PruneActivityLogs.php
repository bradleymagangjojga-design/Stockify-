<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class PruneActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     *   php artisan activity:prune
     *   php artisan activity:prune --days=180
     *   php artisan activity:prune --days=90 --dry-run
     *
     * @var string
     */
    protected $signature = 'activity:prune
                            {--days=90 : Delete logs older than N days}
                            {--dry-run : Show how many rows would be deleted without deleting them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete activity logs older than the specified number of days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days   = (int) $this->option('days');
        $dryRun = (bool) $this->option('dry-run');
        $cutoff = now()->subDays($days);

        // ✅ Sanity check — prevent accidental mass deletion
        if ($days < 1) {
            $this->error('❌ --days must be at least 1. Aborting.');
            return self::FAILURE;
        }

        $this->info("🔍 Scanning for activity logs older than {$days} days (before {$cutoff->format('Y-m-d H:i:s')})...");

        $count = ActivityLog::where('created_at', '<', $cutoff)->count();

        if ($count === 0) {
            $this->info("✅ No logs older than {$days} days to delete.");
            return self::SUCCESS;
        }

        // ✅ Dry-run mode — just report
        if ($dryRun) {
            $this->warn("⚠️  [DRY RUN] Would delete {$count} logs older than {$days} days.");
            $this->line("   Cutoff: {$cutoff->format('Y-m-d H:i:s')}");
            return self::SUCCESS;
        }

        // ✅ Actual deletion with progress bar
        $this->info("🗑️  Deleting {$count} logs older than {$days} days...");

        $deleted = 0;
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        // Delete in chunks to avoid locking the table on huge datasets
        while (true) {
            $chunk = ActivityLog::where('created_at', '<', $cutoff)
                ->limit(1000)
                ->delete();

            if ($chunk === 0) {
                break;
            }

            $deleted += $chunk;
            $bar->advance($chunk);
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Deleted {$deleted} activity logs.");
        $this->line("   Cutoff: {$cutoff->format('Y-m-d H:i:s')}");

        return self::SUCCESS;
    }
}
