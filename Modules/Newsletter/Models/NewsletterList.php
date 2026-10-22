<?php
namespace Modules\Newsletter\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Database\Factories\NewsletterListFactory;

class NewsletterList extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Newsletter\Events\NewsletterListWasCreated::class,
        'updated' => \Modules\Newsletter\Events\NewsletterListWasUpdated::class,
        'deleted' => \Modules\Newsletter\Events\NewsletterListWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

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

    public static function findOrCreateDefault(): self
    {
        return static::query()->firstOrCreate(['name' => 'Default']);
    }

    public function subscribers()
    {
        return $this->hasMany(NewsletterSubscriberList::class, 'list_id', 'id');
    }


    public function getSubscribersCountAttribute()
    {
        return NewsletterSubscriberList::where('list_id', $this->id)->count();
    }
}
