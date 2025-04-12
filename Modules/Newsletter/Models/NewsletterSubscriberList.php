<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Newsletter\Database\Factories\NewsletterSubscriberListFactory;

class NewsletterSubscriberList extends Model
{
    use HasFactory;

    protected $table = 'newsletter_subscribers_lists';

    protected $fillable =[
        'subscriber_id',
        'list_id',
    ];

    public function campaignsSendLog()
    {
        return $this->hasMany(NewsletterCampaignsSendLog::class, 'subscriber_id', 'subscriber_id');
    }

    protected static function newFactory()
    {
        return NewsletterSubscriberListFactory::new();
    }
}
