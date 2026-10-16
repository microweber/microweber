<?php

namespace Modules\Attributes\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Attribute extends Model
{
    // Lifecycle events so other modules can react to Attribute changes.
    protected $dispatchesEvents = [
        'created' => \Modules\Attributes\Events\AttributeWasCreated::class,
        'updated' => \Modules\Attributes\Events\AttributeWasUpdated::class,
        'deleted' => \Modules\Attributes\Events\AttributeWasDeleted::class,
    ];

    use CacheableQueryBuilderTrait;

    protected $table = 'attributes';

    protected $fillable = [
        'attribute_name',
        'attribute_value',
        'rel_type',
        'rel_id',
        'attribute_type',
        'session_id',
        'updated_at',
        'created_at',
        'created_by',
        'edited_by',
    ];
}
