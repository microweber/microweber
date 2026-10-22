<?php

namespace Modules\Newsletter\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Database\Factories\NewsletterSubscriberFactory;

class NewsletterSubscriber extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Newsletter\Events\NewsletterSubscriberWasCreated::class,
        'updated' => \Modules\Newsletter\Events\NewsletterSubscriberWasUpdated::class,
        'deleted' => \Modules\Newsletter\Events\NewsletterSubscriberWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;

    protected $table = 'newsletter_subscribers';

    protected $fillable = [
        'email',
        'name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'is_subscribed'
    ];

    protected static function newFactory()
    {
        return NewsletterSubscriberFactory::new();
    }

    public function lists()
    {
        return $this->belongsToMany(NewsletterList::class, 'newsletter_subscribers_lists', 'subscriber_id', 'list_id');
    }
}
