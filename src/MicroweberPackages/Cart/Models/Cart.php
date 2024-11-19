<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Cart\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Cart\Models\ModelFilters\CartFilter;
use MicroweberPackages\Order\Models\Order;

class Cart extends Model
{
    public $table = 'cart';

    public $fillable = [
        'rel_type',
        'rel_id',

        'price',
        'currency',
        'qty',

        'order_id',
        'order_completed',

        'description',
        'link',
        'other_info',
        'custom_fields_data',
        'custom_fields_json',

    ];


    protected $casts = [
        'custom_fields_json' => 'array',
    ];

    use Filterable;

//    protected static function boot() {
//        parent::boot();
//
//        static::updating(function ($model) {
//            dd($model);
//        });
//    }

    public function modelFilter()
    {
        return $this->provideFilter(CartFilter::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id','order_id');
    }

    public function products()
    {
        return $this->hasMany(\Modules\Product\Models\Product::class, 'id', 'rel_id');
    }
}
