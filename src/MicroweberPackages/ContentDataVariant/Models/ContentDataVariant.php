<?php


namespace MicroweberPackages\ContentDataVariant\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;

class ContentDataVariant extends Model
{
    use CacheableQueryBuilderTrait;
    use HasCreatedByFieldsTrait;

    protected $table = 'content_data_variants';

    public $timestamps = true;


    protected $fillable = [
        'rel_type',
        'rel_id',
        'custom_field_id',
        'custom_field_value_id',
    ];

}
