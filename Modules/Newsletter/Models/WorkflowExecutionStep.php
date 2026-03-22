<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowExecutionStep extends Model
{
    use HasFactory;

    public $table = 'workflow_execution_steps';

    // Step statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_RUNNING = 'running';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    public $fillable = [
        'execution_id',
        'node_id',
        'status',
        'step_number',
        'input_data',
        'output_data',
        'started_at',
        'completed_at',
        'error_message',
    ];

    public $casts = [
        'input_data' => 'array',
        'output_data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'step_number' => 'integer',
    ];

    /**
     * Get the execution this step belongs to.
     */
    public function execution(): BelongsTo
    {
        return $this->belongsTo(WorkflowExecution::class, 'execution_id');
    }

    /**
     * Get the node this step executes.
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class, 'node_id');
    }

    /**
     * Mark step as started.
     */
    public function markAsStarted(): void
    {
        $this->status = self::STATUS_RUNNING;
        $this->started_at = now();
        $this->save();
    }

    /**
     * Mark step as completed.
     */
    public function markAsCompleted(array $outputData = []): void
    {
        $this->status = self::STATUS_COMPLETED;
        $this->output_data = $outputData;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Mark step as failed.
     */
    public function markAsFailed(string $message, array $outputData = []): void
    {
        $this->status = self::STATUS_FAILED;
        $this->error_message = $message;
        $this->output_data = $outputData;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Mark step as skipped.
     */
    public function markAsSkipped(): void
    {
        $this->status = self::STATUS_SKIPPED;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Get duration of step execution in seconds.
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
     * Check if step is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if step has failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Scope to get pending steps.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get running steps.
     */
    public function scopeRunning($query)
    {
        return $query->where('status', self::STATUS_RUNNING);
    }

    /**
     * Scope to get completed steps.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope to get failed steps.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }
}
