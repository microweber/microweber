<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Newsletter\Database\Factories\WorkflowExecutionFactory;

class WorkflowExecution extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return WorkflowExecutionFactory::new();
    }

    public $table = 'workflow_executions';

    // Execution statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_RUNNING = 'running';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    // Trigger sources
    public const SOURCE_EVENT = 'event';
    public const SOURCE_SCHEDULE = 'schedule';
    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_API = 'api';

    public $fillable = [
        'workflow_id',
        'execution_key',
        'status',
        'trigger_source',
        'trigger_data',
        'started_at',
        'completed_at',
        'current_step',
        'total_steps',
        'execution_log',
        'error_message',
        'user_id',
    ];

    public $casts = [
        'trigger_data' => 'array',
        'execution_log' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'current_step' => 'integer',
        'total_steps' => 'integer',
    ];

    /**
     * Get the workflow this execution belongs to.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * Get all steps in this execution.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowExecutionStep::class, 'execution_id')->orderBy('step_number');
    }

    /**
     * Mark execution as started.
     */
    public function markAsStarted(): void
    {
        $this->status = self::STATUS_RUNNING;
        $this->started_at = now();
        $this->save();
    }

    /**
     * Mark execution as completed.
     */
    public function markAsCompleted(): void
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        $this->current_step = $this->total_steps;
        $this->save();
    }

    /**
     * Mark execution as failed.
     */
    public function markAsFailed(string $message = null): void
    {
        $this->status = self::STATUS_FAILED;
        $this->error_message = $message;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Mark execution as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->status = self::STATUS_CANCELLED;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Update execution progress.
     */
    public function updateProgress(int $currentStep, ?string $logMessage = null): void
    {
        $this->current_step = $currentStep;

        if ($logMessage) {
            $log = $this->execution_log ?? [];
            $log[] = [
                'step' => $currentStep,
                'message' => $logMessage,
                'timestamp' => now()->toIso8601String(),
            ];
            $this->execution_log = $log;
        }

        $this->save();
    }

    /**
     * Check if execution is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if execution is running.
     */
    public function isRunning(): bool
    {
        return $this->status === self::STATUS_RUNNING;
    }

    /**
     * Check if execution has failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Get duration of execution in seconds.
     */
    public function getDuration(): ?int
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->completed_at ?? now();
        return $this->started_at->diffInSeconds($endTime);
    }

    /**
     * Scope to get pending executions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get running executions.
     */
    public function scopeRunning($query)
    {
        return $query->where('status', self::STATUS_RUNNING);
    }

    /**
     * Scope to get completed executions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope to get failed executions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Get execution statistics.
     */
    public static function getStatistics(int $workflowId = null): array
    {
        $query = self::query();

        if ($workflowId) {
            $query->where('workflow_id', $workflowId);
        }

        return [
            'total' => $query->count(),
            'pending' => $query->clone()->pending()->count(),
            'running' => $query->clone()->running()->count(),
            'completed' => $query->clone()->completed()->count(),
            'failed' => $query->clone()->failed()->count(),
            'cancelled' => $query->clone()->where('status', self::STATUS_CANCELLED)->count(),
            'success_rate' => $query->clone()->completed()->count() / max($query->clone()->count(), 1) * 100,
        ];
    }
}
