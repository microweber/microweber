<?php
namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Database\Factories\NewsletterListFactory;

class NewsletterList extends Model
{
    use HasFactory;

    public $table = 'newsletter_lists';

    public $fillable = [
        'name',
        'description',
        'is_public'
    ];

    protected static function newFactory()
    {
        return NewsletterListFactory::new();
    }

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
