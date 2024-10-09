<?php

namespace Modules\Tax\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;


class TaxType extends Model
{
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
