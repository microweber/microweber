<?php

namespace Modules\Content\Models;



use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;


class ContentRelated extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Content\Events\ContentRelatedWasCreated::class,
        'updated' => \Modules\Content\Events\ContentRelatedWasUpdated::class,
        'deleted' => \Modules\Content\Events\ContentRelatedWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use MaxPositionTrait;

    use CacheableQueryBuilderTrait;

    public $cacheTagsToClear = ['content', 'categories'];

    protected $table = 'content_related';

    protected $fillable = [
        'content_id',
        'related_content_id',
        'position'
    ];

}
