<?php

namespace MicroweberPackages\Monitoring\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Monitoring\Models\ErrorTracking;
use Illuminate\Support\Facades\DB;

class CleanupErrorTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:cleanup-errors
                            {--days=90 : Number of days to keep resolved errors}
                            {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old resolved errors from error tracking';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        // Count errors to be deleted
        $resolvedCount = ErrorTracking::where('is_resolved', true)
            ->where('updated_at', '<', $cutoffDate)
            ->count();

        $oldUnresolvedCount = ErrorTracking::where('is_resolved', false)
            ->where('created_at', '<', $cutoffDate)
            ->count();

        $totalToDelete = $resolvedCount + $oldUnresolvedCount;

        if ($totalToDelete === 0) {
            $this->info('No old errors to clean up.');
            return self::SUCCESS;
        }

        $this->info("Found {$totalToDelete} old error records to delete:");
        $this->info("  - {$resolvedCount} resolved errors older than {$days} days");
        $this->info("  - {$oldUnresolvedCount} unresolved errors older than {$days} days");

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to proceed with deletion?')) {
                $this->info('Cleanup cancelled.');
                return self::SUCCESS;
            }
        }

        // Delete resolved errors
        $deletedResolved = ErrorTracking::where('is_resolved', true)
            ->where('updated_at', '<', $cutoffDate)
            ->delete();

        // Delete old unresolved errors
        $deletedUnresolved = ErrorTracking::where('is_resolved', false)
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("Successfully deleted {$deletedResolved} resolved errors.");
        $this->info("Successfully deleted {$deletedUnresolved} old unresolved errors.");

        // Get current statistics
        $stats = [
            'total' => ErrorTracking::count(),
            'unresolved' => ErrorTracking::unresolved()->count(),
            'critical' => ErrorTracking::critical()->count(),
        ];

        $this->newLine();
        $this->info('Current error tracking statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Errors', $stats['total']],
                ['Unresolved Errors', $stats['unresolved']],
                ['Critical Errors', $stats['critical']],
            ]
        );

        return self::SUCCESS;
    }
}
