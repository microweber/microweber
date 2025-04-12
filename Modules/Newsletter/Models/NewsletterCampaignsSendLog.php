<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Newsletter\Database\Factories\NewsletterCampaignsSendLogFactory;

class NewsletterCampaignsSendLog extends Model
{
    use HasFactory;

    public $table = 'newsletter_campaigns_send_log';

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'is_sent',
    ];

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscriber::class, 'subscriber_id');
    }

    protected static function newFactory()
    {
        return NewsletterCampaignsSendLogFactory::new();
    }
}
