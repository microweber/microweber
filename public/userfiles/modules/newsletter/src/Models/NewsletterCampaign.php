<?php

namespace MicroweberPackages\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterSubscribersList;

class NewsletterCampaign extends Model
{
    public $table = 'newsletter_campaigns';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_CANCELED = 'canceled';

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_PENDING = 'pending';
    public const STATUS_QUEUED = 'queued';

    public const STATUS_FAILED = 'failed';

    public const DELIVERY_TYPE_SEND_NOW = 'send_now';
    public const DELIVERY_TYPE_SCHEDULE = 'schedule';

    public $fillable = [
        'sender_account_id',
        'email_template_id',
        'list_id',
        'name',
        'subject',
        'from_name',
        'from_email',
        'sending_limit_per_day',
        'is_scheduled',
        'scheduled_at',
        'scheduled_timezone',
        'is_done',
        'recipients_from',
        'delivery_type',
        'status',
        'email_content_html',
        'email_content_type',
        'email_attached_files',
        'enable_email_attachments',
        'delay_between_sending_emails'
    ];

    public $casts = [
        'email_attached_files' => 'array',
    ];

    public function senderAccount()
    {
        return $this->hasOne(NewsletterSenderAccount::class, 'id', 'sender_account_id');
    }

    public function list()
    {
        return $this->hasOne(NewsletterList::class, 'id', 'list_id');
    }

    public function listName()
    {
        return $this->list->name;
    }

    public function countSubscribers()
    {
        if ($this->recipients_from == 'specific_list') {
            return NewsletterSubscriberList::where('list_id', $this->list_id)->count();
        } else {
            return NewsletterSubscriber::all()->count();
        }
    }

    public function getSubscribersAttribute()
    {
        return NewsletterSubscriberList::where('list_id', $this->list_id)->count();

    }

    public function getOpenedAttribute()
    {
        return NewsletterCampaignPixel::where('campaign_id', $this->id)->count();
    }

    public function getClickedAttribute()
    {
        return NewsletterCampaignClickedLink::where('campaign_id', $this->id)->count();
    }

    public function getStatusAttribute()
    {
        if (isset($this->attributes['status'])) {
            return $this->attributes['status'];
        }
        return self::STATUS_DRAFT;
    }

    public static function markAsFinished($campaignId)
    {
        $campaign = NewsletterCampaign::where('id', $campaignId)->first();
        if ($campaign) {
            $campaign->status = self::STATUS_FINISHED;
            $campaign->is_done = 1;
            $campaign->save();
        }
    }
}
