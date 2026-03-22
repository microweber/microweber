<?php

namespace Modules\Backup\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Backup Schedule Model
 *
 * Manages automated backup schedules with flexible frequency options
 * and retention policies.
 */
class BackupSchedule extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backup_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'type',
        'tables',
        'include_media',
        'frequency',
        'time',
        'day_of_week',
        'day_of_month',
        'retention_days',
        'is_active',
        'last_run_at',
        'next_run_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tables' => 'array',
        'include_media' => 'boolean',
        'day_of_week' => 'integer',
        'day_of_month' => 'integer',
        'retention_days' => 'integer',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    /**
     * Get backup histories for this schedule.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(BackupHistory::class, 'backup_schedule_id');
    }

    /**
     * Calculate and update the next run time based on frequency.
     */
    public function calculateNextRun(): void
    {
        $now = Carbon::now();
        $nextRun = null;

        switch ($this->frequency) {
            case 'hourly':
                $nextRun = $now->copy()->addHour()->startOfHour();
                break;
            case 'daily':
                $time = $this->time ?? '00:00';
                $nextRun = $now->copy()->addDay()->setTimeFromTimeString($time);
                if ($nextRun->isPast()) {
                    $nextRun = $nextRun->addDay();
                }
                break;
            case 'weekly':
                $dayOfWeek = $this->day_of_week ?? 0; // Sunday
                $time = $this->time ?? '00:00';
                $nextRun = $now->copy()->next($dayOfWeek)->setTimeFromTimeString($time);
                if ($nextRun->isPast()) {
                    $nextRun = $nextRun->addWeek();
                }
                break;
            case 'monthly':
                $dayOfMonth = $this->day_of_month ?? 1;
                $time = $this->time ?? '00:00';
                $nextRun = $now->copy()->startOfMonth()->addMonth()->setDay($dayOfMonth)->setTimeFromTimeString($time);
                break;
        }

        $this->next_run_at = $nextRun;
        $this->save();
    }

    /**
     * Check if this schedule is due to run.
     */
    public function isDue(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->next_run_at) {
            return true;
        }

        return Carbon::now()->gte($this->next_run_at);
    }

    /**
     * Mark schedule as run and update next run time.
     */
    public function markAsRun(): void
    {
        $this->last_run_at = Carbon::now();
        $this->calculateNextRun();
    }

    /**
     * Scope for active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for schedules that are due.
     */
    public function scopeDue($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('next_run_at')
                    ->orWhere('next_run_at', '<=', Carbon::now());
            });
    }

    /**
     * Get frequency options for UI.
     *
     * @return array<string, string>
     */
    public static function getFrequencyOptions(): array
    {
        return [
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ];
    }

    /**
     * Get day of week options for UI.
     *
     * @return array<int, string>
     */
    public static function getDayOfWeekOptions(): array
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
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
