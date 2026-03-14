<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Database\Factories\WebhookLogFactory;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'event_type',
        'event_id',
        'payload',
        'status',
        'attempts',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_RETRYING = 'retrying';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): WebhookLogFactory
    {
        return new WebhookLogFactory();
    }

    /**
     * Scope to get pending webhooks.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get failed webhooks that need retry.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Mark webhook as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark webhook as failed with error message.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Mark webhook as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
            'attempts' => $this->attempts + 1,
        ]);
    }

    /**
     * Mark webhook for retry.
     */
    public function markForRetry(): void
    {
        $this->update([
            'status' => self::STATUS_RETRYING,
        ]);
    }

    /**
     * Check if webhook can be retried.
     */
    public function canRetry(int $maxRetries = 3): bool
    {
        return $this->attempts < $maxRetries && in_array($this->status, [self::STATUS_FAILED, self::STATUS_RETRYING]);
    }
}
