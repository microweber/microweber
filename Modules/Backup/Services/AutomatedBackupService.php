<?php

namespace Modules\Backup\Services;

use Modules\Backup\Backup;
use Modules\Backup\Models\BackupHistory;
use Modules\Backup\Models\BackupSchedule;
use Modules\Backup\SessionStepper;
use Modules\Backup\Notifications\BackupCompletedNotification;
use Modules\Backup\Notifications\BackupFailedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Automated Backup Service
 *
 * Handles scheduled backup execution, retention policies,
 * and notification management.
 */
class AutomatedBackupService
{
    /**
     * Process due backup schedules.
     *
     * @return array<string, mixed>
     */
    public function processDueSchedules(): array
    {
        $schedules = BackupSchedule::due()->get();
        $results = [
            'processed' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($schedules as $schedule) {
            try {
                $this->executeSchedule($schedule);
                $results['processed']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ];
                Log::error('Failed to process backup schedule', [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Execute a specific backup schedule.
     *
     * @param BackupSchedule $schedule
     * @return BackupHistory
     * @throws \Exception
     */
    public function executeSchedule(BackupSchedule $schedule): BackupHistory
    {
        // Create history record
        $history = new BackupHistory();
        $history->backup_schedule_id = $schedule->id;
        $history->type = 'scheduled';
        $history->backup_type = $schedule->type;
        $history->tables = $schedule->tables;
        $history->include_media = $schedule->include_media;
        $history->status = 'pending';
        $history->save();

        try {
            // Mark as running
            $history->markAsRunning();
            $schedule->markAsRun();

            // Generate filename
            $filename = $this->generateFilename($schedule);
            $backupPath = backup_location() . $filename;

            // Configure and execute backup
            $backup = new Backup();
            $backup->setBackupFileName($filename);

            // Generate session ID
            $sessionId = SessionStepper::generateSessionId(20, [
                'schedule_id' => $schedule->id,
                'type' => $schedule->type,
                'filename' => $filename,
            ]);
            $backup->setSessionId($sessionId);

            // Configure based on backup type
            $this->configureBackupByType($backup, $schedule);

            // Execute backup
            $result = $backup->start();

            if (isset($result['error'])) {
                throw new \Exception($result['error']);
            }

            // Get file size
            $size = file_exists($backupPath) ? filesize($backupPath) : 0;

            // Mark as completed
            $history->markAsCompleted($filename, $backupPath, $size);

            // Send success notification
            $this->sendSuccessNotification($history);

            // Apply retention policy
            $this->applyRetentionPolicy($schedule);

            Log::info('Scheduled backup completed successfully', [
                'schedule_id' => $schedule->id,
                'history_id' => $history->id,
                'filename' => $filename,
                'size' => $size,
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            $history->markAsFailed($e->getMessage());

            // Send failure notification
            $this->sendFailureNotification($history, $e);

            Log::error('Scheduled backup failed', [
                'schedule_id' => $schedule->id,
                'history_id' => $history->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $history;
    }

    /**
     * Execute a manual backup.
     *
     * @param string $backupType
     * @param array $options
     * @return BackupHistory
     * @throws \Exception
     */
    public function executeManualBackup(string $backupType, array $options = []): BackupHistory
    {
        // Create history record
        $history = new BackupHistory();
        $history->type = 'manual';
        $history->backup_type = $backupType;
        $history->tables = $options['tables'] ?? null;
        $history->include_media = $options['include_media'] ?? true;
        $history->status = 'pending';
        $history->save();

        try {
            // Mark as running
            $history->markAsRunning();

            // Generate filename
            $filename = $this->generateFilename(null, $backupType);
            $backupPath = backup_location() . $filename;

            // Configure and execute backup
            $backup = new Backup();
            $backup->setBackupFileName($filename);

            // Generate session ID
            $sessionId = SessionStepper::generateSessionId(20, [
                'type' => $backupType,
                'filename' => $filename,
                'manual' => true,
            ]);
            $backup->setSessionId($sessionId);

            // Configure based on backup type
            if ($backupType === 'customBackup' && !empty($options['tables'])) {
                $backup->setBackupTables($options['tables']);
                $backup->setAllowSkipTables(false);
            } elseif ($backupType === 'fullBackup') {
                $backup->setAllowSkipTables(false);
                $backup->setBackupAllData(true);
                $backup->setBackupMedia($options['include_media'] ?? true);
                $backup->setBackupWithZip(true);
            } else {
                // contentBackup
                $backup->setType('json');
                $backup->setAllowSkipTables(true);
                $backup->setBackupAllData(true);
                $backup->setBackupMedia($options['include_media'] ?? true);
                $backup->setBackupWithZip(true);
            }

            // Execute backup
            $result = $backup->start();

            if (isset($result['error'])) {
                throw new \Exception($result['error']);
            }

            // Get file size
            $size = file_exists($backupPath) ? filesize($backupPath) : 0;

            // Mark as completed
            $history->markAsCompleted($filename, $backupPath, $size);

            Log::info('Manual backup completed successfully', [
                'history_id' => $history->id,
                'filename' => $filename,
                'size' => $size,
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            $history->markAsFailed($e->getMessage());

            Log::error('Manual backup failed', [
                'history_id' => $history->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $history;
    }

    /**
     * Configure backup based on type.
     *
     * @param Backup $backup
     * @param BackupSchedule $schedule
     */
    private function configureBackupByType(Backup $backup, BackupSchedule $schedule): void
    {
        switch ($schedule->type) {
            case 'fullBackup':
                $backup->setAllowSkipTables(false);
                $backup->setBackupAllData(true);
                $backup->setBackupMedia($schedule->include_media);
                $backup->setBackupWithZip(true);
                break;

            case 'customBackup':
                if (!empty($schedule->tables)) {
                    $backup->setBackupTables($schedule->tables);
                }
                $backup->setAllowSkipTables(false);
                $backup->setBackupMedia($schedule->include_media);
                break;

            case 'contentBackup':
            default:
                $backup->setType('json');
                $backup->setAllowSkipTables(true);
                $backup->setBackupAllData(true);
                $backup->setBackupMedia($schedule->include_media);
                $backup->setBackupWithZip(true);
                break;
        }
    }

    /**
     * Generate filename for backup.
     *
     * @param BackupSchedule|null $schedule
     * @param string|null $backupType
     * @return string
     */
    private function generateFilename(?BackupSchedule $schedule, ?string $backupType = null): string
    {
        $type = $backupType ?? ($schedule->type ?? 'backup');
        $timestamp = date('Y-m-d_H-i-s');
        $name = $schedule->name ?? 'auto';
        $filename = "{$name}_{$type}_{$timestamp}.zip";

        // Sanitize filename
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);

        return $filename;
    }

    /**
     * Apply retention policy for a schedule.
     *
     * @param BackupSchedule $schedule
     */
    private function applyRetentionPolicy(BackupSchedule $schedule): void
    {
        $retentionDays = $schedule->retention_days ?? 7;

        if ($retentionDays <= 0) {
            return;
        }

        $cutoffDate = Carbon::now()->subDays($retentionDays);

        $oldBackups = BackupHistory::where('backup_schedule_id', $schedule->id)
            ->where('status', 'completed')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        foreach ($oldBackups as $backup) {
            try {
                // Delete file if it exists
                if ($backup->filepath && file_exists($backup->filepath)) {
                    unlink($backup->filepath);
                }

                // Delete history record
                $backup->delete();

                Log::info('Old backup deleted per retention policy', [
                    'schedule_id' => $schedule->id,
                    'backup_id' => $backup->id,
                    'filename' => $backup->filename,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to delete old backup', [
                    'schedule_id' => $schedule->id,
                    'backup_id' => $backup->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Send success notification.
     *
     * @param BackupHistory $history
     */
    private function sendSuccessNotification(BackupHistory $history): void
    {
        try {
            // TODO: Implement notification to admin users
            // Notification::send($adminUsers, new BackupCompletedNotification($history));
        } catch (\Exception $e) {
            Log::error('Failed to send backup success notification', [
                'history_id' => $history->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send failure notification.
     *
     * @param BackupHistory $history
     * @param \Exception $exception
     */
    private function sendFailureNotification(BackupHistory $history, \Exception $exception): void
    {
        try {
            // TODO: Implement notification to admin users
            // Notification::send($adminUsers, new BackupFailedNotification($history, $exception));
        } catch (\Exception $e) {
            Log::error('Failed to send backup failure notification', [
                'history_id' => $history->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Clean up old pending backups that never completed.
     *
     * @param int $hours
     * @return int
     */
    public function cleanupStaleBackups(int $hours = 24): int
    {
        $cutoffDate = Carbon::now()->subHours($hours);

        $staleBackups = BackupHistory::whereIn('status', ['pending', 'running'])
            ->where('started_at', '<', $cutoffDate)
            ->get();

        $count = 0;
        foreach ($staleBackups as $backup) {
            $backup->markAsFailed('Backup timed out after ' . $hours . ' hours');
            $count++;
        }

        if ($count > 0) {
            Log::info('Cleaned up stale backup records', [
                'count' => $count,
                'hours' => $hours,
            ]);
        }

        return $count;
    }

    /**
     * Get backup statistics.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        $totalBackups = BackupHistory::count();
        $completedBackups = BackupHistory::completed()->count();
        $failedBackups = BackupHistory::failed()->count();
        $runningBackups = BackupHistory::running()->count();

        $totalSize = BackupHistory::completed()->sum('size') ?? 0;

        $last24Hours = BackupHistory::completed()
            ->where('completed_at', '>=', Carbon::now()->subHours(24))
            ->count();

        $last7Days = BackupHistory::completed()
            ->where('completed_at', '>=', Carbon::now()->subDays(7))
            ->count();

        return [
            'total_backups' => $totalBackups,
            'completed_backups' => $completedBackups,
            'failed_backups' => $failedBackups,
            'running_backups' => $runningBackups,
            'success_rate' => $totalBackups > 0 ? round(($completedBackups / $totalBackups) * 100, 2) : 0,
            'total_size' => $totalSize,
            'total_size_formatted' => format_bytes($totalSize),
            'last_24_hours' => $last24Hours,
            'last_7_days' => $last7Days,
        ];
    }

    /**
     * Get storage usage information.
     *
     * @return array<string, mixed>
     */
    public function getStorageInfo(): array
    {
        $backupPath = backup_location();

        if (!is_dir($backupPath)) {
            return [
                'path' => $backupPath,
                'exists' => false,
                'total_size' => 0,
                'total_size_formatted' => 'N/A',
                'file_count' => 0,
            ];
        }

        $size = 0;
        $count = 0;

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($backupPath)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
                $count++;
            }
        }

        return [
            'path' => $backupPath,
            'exists' => true,
            'total_size' => $size,
            'total_size_formatted' => format_bytes($size),
            'file_count' => $count,
        ];
    }
}
