<?php
namespace MicroweberPackages\Tax;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;


class TaxType extends Model
{
    use CacheableQueryBuilderTrait;

    protected $fillable = [
        'name',
        'type',
        'rate',
        'compound_tax',
        'collective_tax',
        'description'
    ];

    protected $casts = [
        'percent' => 'float'
    ];

}
