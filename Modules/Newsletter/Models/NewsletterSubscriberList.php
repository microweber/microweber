<?php

namespace Modules\Newsletter\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Newsletter\Database\Factories\NewsletterSubscriberListFactory;

class NewsletterSubscriberList extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Newsletter\Events\NewsletterSubscriberListWasCreated::class,
        'updated' => \Modules\Newsletter\Events\NewsletterSubscriberListWasUpdated::class,
        'deleted' => \Modules\Newsletter\Events\NewsletterSubscriberListWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

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
