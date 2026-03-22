<?php

namespace Modules\Backup\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Backup History Model
 *
 * Tracks all backup operations including manual and scheduled backups,
 * with status tracking and metadata.
 */
class BackupHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backup_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'backup_schedule_id',
        'type',
        'backup_type',
        'filename',
        'filepath',
        'size',
        'status',
        'tables',
        'include_media',
        'error_message',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tables' => 'array',
        'include_media' => 'boolean',
        'size' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the schedule that owns this backup.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(BackupSchedule::class, 'backup_schedule_id');
    }

    /**
     * Mark backup as running.
     */
    public function markAsRunning(): void
    {
        $this->status = 'running';
        $this->started_at = Carbon::now();
        $this->save();
    }

    /**
     * Mark backup as completed.
     */
    public function markAsCompleted(string $filename, string $filepath, int $size): void
    {
        $this->status = 'completed';
        $this->filename = $filename;
        $this->filepath = $filepath;
        $this->size = $size;
        $this->completed_at = Carbon::now();
        $this->save();
    }

    /**
     * Mark backup as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->status = 'failed';
        $this->error_message = $errorMessage;
        $this->completed_at = Carbon::now();
        $this->save();
    }

    /**
     * Scope for completed backups.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed backups.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending backups.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for running backups.
     */
    public function scopeRunning($query)
    {
        return $query->where('status', 'running');
    }

    /**
     * Scope for manual backups.
     */
    public function scopeManual($query)
    {
        return $query->where('type', 'manual');
    }

    /**
     * Scope for scheduled backups.
     */
    public function scopeScheduled($query)
    {
        return $query->where('type', 'scheduled');
    }

    /**
     * Scope for backups older than given days.
     */
    public function scopeOlderThan($query, int $days)
    {
        return $query->where('created_at', '<', Carbon::now()->subDays($days));
    }

    /**
     * Scope for backups within a date range.
     */
    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Get formatted size.
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->size) {
            return 'N/A';
        }
        return format_bytes($this->size);
    }

    /**
     * Get duration in seconds.
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }
        return $this->started_at->diffInSeconds($this->completed_at);
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        $duration = $this->duration;
        if ($duration === null) {
            return 'N/A';
        }
        if ($duration < 60) {
            return $duration . ' seconds';
        }
        if ($duration < 3600) {
            return round($duration / 60, 1) . ' minutes';
        }
        return round($duration / 3600, 1) . ' hours';
    }

    /**
     * Check if backup file exists.
     */
    public function fileExists(): bool
    {
        return $this->filepath && file_exists($this->filepath);
    }

    /**
     * Get status options for UI.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'running' => 'Running',
            'completed' => 'Completed',
            'failed' => 'Failed',
        ];
    }

    /**
     * Get type options for UI.
     *
     * @return array<string, string>
     */
    public static function getTypeOptions(): array
    {
        return [
            'manual' => 'Manual',
            'scheduled' => 'Scheduled',
        ];
    }

    /**
     * Get backup type options for UI.
     *
     * @return array<string, string>
     */
    public static function getBackupTypeOptions(): array
    {
        return [
            'contentBackup' => 'Content Backup',
            'fullBackup' => 'Full Backup',
            'customBackup' => 'Custom Backup',
        ];
    }
}
