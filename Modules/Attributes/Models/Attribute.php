<?php

namespace Modules\Attributes\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Attribute extends Model
{
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
