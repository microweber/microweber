<?php
namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterList extends Model
{
    public $table = 'newsletter_lists';

    public $fillable = [
        'name',
    ];

    public function subscribers()
    {
        return $this->hasMany(NewsletterSubscriberList::class, 'list_id', 'id');
    }

    public function getSubscribersAttribute()
    {
        $subscribers = NewsletterSubscriberList::where('list_id', $this->id)->count();

        return $subscribers;
    }
}
