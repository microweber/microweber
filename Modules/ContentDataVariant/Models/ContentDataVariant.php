<?php


namespace Modules\ContentDataVariant\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class ContentDataVariant extends Model
{
    use FiresModelLifecycleEvents;

    use CacheableQueryBuilderTrait;

    protected $table = 'content_data_variants';

    public $timestamps = true;


    protected $fillable = [
        'rel_type',
        'rel_id',
        'custom_field_id',
        'custom_field_value_id',
    ];

}
