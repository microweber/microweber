<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterAutomationQueue extends Model
{
    use HasFactory;

    public $table = 'newsletter_automation_queue';

    protected static function newFactory()
    {
        return \Modules\Newsletter\Database\Factories\NewsletterAutomationQueueFactory::new();
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELED = 'canceled';

    public $fillable = [
        'campaign_id',
        'subscriber_id',
        'email',
        'trigger_event',
        'event_data',
        'scheduled_at',
        'sent_at',
        'status',
        'error_message',
    ];

    protected $casts = [
        'event_data' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(NewsletterCampaign::class, 'campaign_id');
    }

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscriber::class, 'subscriber_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeReadyToSend($query)
    {
        return $query->pending()
            ->where('scheduled_at', '<=', now());
    }

    public function scopeByEvent($query, string $event)
    {
        return $query->where('trigger_event', $event);
    }

    public function markAsSent(): void
    {
        $this->status = self::STATUS_SENT;
        $this->sent_at = now();
        $this->save();
    }

    public function markAsFailed(string $message = null): void
    {
        $this->status = self::STATUS_FAILED;
        $this->error_message = $message;
        $this->save();
    }

    public function markAsCanceled(): void
    {
        $this->status = self::STATUS_CANCELED;
        $this->save();
    }
}
