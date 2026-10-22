<?php


namespace Modules\ContentData\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;


class ContentData extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\ContentData\Events\ContentDataWasCreated::class,
        'updated' => \Modules\ContentData\Events\ContentDataWasUpdated::class,
        'deleted' => \Modules\ContentData\Events\ContentDataWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use CacheableQueryBuilderTrait;
    use HasCreatedByFieldsTrait;

    protected $table = 'content_data';

    public $timestamps = true;


    protected $fillable = [
        'id',
        'rel_type',
        'rel_id',
        'field_value',
        'field_name',
        'content_id',
        'created_at',
        'updated_at',
        'edited_by',
        'created_by'
    ];


}
