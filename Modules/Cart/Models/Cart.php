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

namespace Modules\Cart\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Modules\Cart\Models\ModelFilters\CartFilter;
use Modules\Order\Models\Order;

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

    ];


    protected $casts = [
         'custom_fields_data' => 'array',
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
