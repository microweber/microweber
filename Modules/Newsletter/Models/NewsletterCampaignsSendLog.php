<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterCampaignsSendLog extends Model
{

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

}
