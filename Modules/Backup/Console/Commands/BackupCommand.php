<?php

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use Modules\Backup\Services\AutomatedBackupService;
use Modules\Backup\Models\BackupSchedule;
use Modules\Backup\Models\BackupHistory;
use Illuminate\Support\Facades\Log;

/**
 * Backup Command
 *
 * Artisan command for running automated backups and managing schedules.
 *
 * Usage:
 *   php artisan backup:run              # Process all due schedules
 *   php artisan backup:run --schedule=1 # Run specific schedule
 *   php artisan backup:run --manual     # Create manual backup
 *   php artisan backup:run --type=fullBackup --manual
 *   php artisan backup:stats            # Show backup statistics
 *   php artisan backup:cleanup          # Clean stale backups
 */
class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run
        {--schedule= : Run a specific schedule by ID}
        {--manual : Create a manual backup}
        {--type=contentBackup : Backup type (contentBackup, fullBackup, customBackup)}
        {--tables= : Comma-separated list of tables for custom backup}
        {--include-media : Include media files}
        {--filename= : Custom filename for manual backup}
        {--stats : Show backup statistics}
        {--cleanup : Clean up stale backup records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run automated backups or show backup statistics';

    /**
     * Execute the console command.
     *
     * @param AutomatedBackupService $backupService
     * @return int
     */
    public function handle(AutomatedBackupService $backupService): int
    {
        if ($this->option('stats')) {
            return $this->showStats($backupService);
        }

        if ($this->option('cleanup')) {
            return $this->cleanupStaleBackups($backupService);
        }

        if ($this->option('schedule')) {
            return $this->runSpecificSchedule($backupService);
        }

        if ($this->option('manual')) {
            return $this->runManualBackup($backupService);
        }

        // Default: process due schedules
        return $this->processDueSchedules($backupService);
    }

    /**
     * Process all due backup schedules.
     *
     * @param AutomatedBackupService $backupService
     * @return int
     */
    private function processDueSchedules(AutomatedBackupService $backupService): int
    {
        $this->info('Checking for due backup schedules...');

        $dueSchedules = BackupSchedule::due()->count();

        if ($dueSchedules === 0) {
            $this->warn('No backup schedules are due at this time.');
            return self::SUCCESS;
        }

        $this->info("Found {$dueSchedules} schedule(s) to process.");

        $results = $backupService->processDueSchedules();

        $this->newLine();
        $this->info('Backup processing completed:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Processed', $results['processed']],
                ['Failed', $results['failed']],
            ]
        );

        if (!empty($results['errors'])) {
            $this->newLine();
            $this->error('Errors occurred:');
            foreach ($results['errors'] as $error) {
                $this->error("  - Schedule #{$error['schedule_id']}: {$error['error']}");
            }
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Run a specific schedule.
     *
     * @param AutomatedBackupService $backupService
     * @return int
     */
    private function runSpecificSchedule(AutomatedBackupService $backupService): int
    {
        $scheduleId = $this->option('schedule');

        $this->info("Running backup schedule #{$scheduleId}...");

        $schedule = BackupSchedule::find($scheduleId);

        if (!$schedule) {
            $this->error("Schedule #{$scheduleId} not found.");
            return self::FAILURE;
        }

        if (!$schedule->is_active) {
            $this->error("Schedule #{$scheduleId} is inactive.");
            return self::FAILURE;
        }

        try {
            $history = $backupService->executeSchedule($schedule);

            if ($history->status === 'completed') {
                $this->info('Backup completed successfully!');
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['Filename', $history->filename],
                        ['Size', $history->formatted_size],
                        ['Duration', $history->formatted_duration],
                    ]
                );
                return self::SUCCESS;
            } else {
                $this->error('Backup failed: ' . $history->error_message);
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Manual schedule execution failed', [
                'schedule_id' => $scheduleId,
                'error' => $e->getMessage(),
            ]);
            return self::FAILURE;
        }
    }

    /**
     * Run a manual backup.
     *
     * @param AutomatedBackupService $backupService
     * @return int
     */
    private function runManualBackup(AutomatedBackupService $backupService): int
    {
        $backupType = $this->option('type');

        $this->info("Creating manual {$backupType} backup...");

        $options = [
            'include_media' => $this->option('include-media'),
        ];

        if ($backupType === 'customBackup') {
            $tables = $this->option('tables');
            if ($tables) {
                $options['tables'] = explode(',', $tables);
            } else {
                $this->error('Tables required for custom backup. Use --tables=table1,table2');
                return self::FAILURE;
            }
        }

        if ($this->option('filename')) {
            $options['filename'] = $this->option('filename');
        }

        try {
            $history = $backupService->executeManualBackup($backupType, $options);

            if ($history->status === 'completed') {
                $this->info('Manual backup completed successfully!');
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['Filename', $history->filename],
                        ['Size', $history->formatted_size],
                        ['Duration', $history->formatted_duration],
                    ]
                );
                return self::SUCCESS;
            } else {
                $this->error('Backup failed: ' . $history->error_message);
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Manual backup failed', [
                'type' => $backupType,
                'error' => $e->getMessage(),
            ]);
            return self::FAILURE;
        }
    }

    /**
     * Show backup statistics.
     *
     * @param AutomatedBackupService $backupService
     * @return int
     */
    private function showStats(AutomatedBackupService $backupService): int
    {
        $this->info('Backup Statistics');
        $this->newLine();

        $stats = $backupService->getStatistics();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Backups', $stats['total_backups']],
                ['Completed', $stats['completed_backups']],
                ['Failed', $stats['failed_backups']],
                ['Running', $stats['running_backups']],
                ['Success Rate', $stats['success_rate'] . '%'],
                ['Total Size', $stats['total_size_formatted']],
                ['Last 24 Hours', $stats['last_24_hours']],
                ['Last 7 Days', $stats['last_7_days']],
            ]
        );

        $this->newLine();
        $storageInfo = $backupService->getStorageInfo();

        $this->info('Storage Information');
        $this->table(
            ['Property', 'Value'],
            [
                ['Backup Path', $storageInfo['path']],
                ['Exists', $storageInfo['exists'] ? 'Yes' : 'No'],
                ['Total Size', $storageInfo['total_size_formatted']],
                ['File Count', $storageInfo['file_count']],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Clean up stale backup records.
     *
     * @param AutomatedBackupService $backupService
     * @return int
     */
    private function cleanupStaleBackups(AutomatedBackupService $backupService): int
    {
        $this->info('Cleaning up stale backup records...');

        $count = $backupService->cleanupStaleBackups();

        if ($count > 0) {
            $this->info("Marked {$count} stale backup(s) as failed.");
        } else {
            $this->info('No stale backup records found.');
        }

        return self::SUCCESS;
    }
}
