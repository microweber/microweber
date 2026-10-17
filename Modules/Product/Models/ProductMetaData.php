<?php

namespace Modules\Product\Models;



use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class ProductMetaData extends Model
{
    use FiresModelLifecycleEvents;


    protected $fillable = [
        'qty',
        'sku',
        'barcode',
        'track_quantity',
        'max_quantity_per_order',
        'sell_oos',
        'physical_product',
        'free_shipping',
        'shipping_fixed_cost',
        'weight_type',
        'params_in_checkout',
        'has_special_price',
        'weight',
        'width',
        'height',
        'depth'
    ];

}
