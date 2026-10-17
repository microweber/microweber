<?php

namespace Modules\Tax\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;


class TaxType extends Model
{
    use FiresModelLifecycleEvents;

    use CacheableQueryBuilderTrait;

    protected $table = 'tax_types';

    protected $fillable = [
        'id',
        'name',
        'type',
        'rate',
        'description',
        'settings'
    ];

    protected $casts = [
        'percent' => 'float',
        'settings' => 'array'
    ];

}
