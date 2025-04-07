<?php

namespace Modules\Product\Models;

use Modules\Cart\Models\Cart;
use Modules\Content\Models\Content;
use Modules\Content\Scopes\ProductScope;
use Modules\ContentDataVariant\Models\ContentDataVariant;
use Modules\CustomFields\Models\CustomFieldValue;
use Modules\Offer\Models\Offer;
use Modules\Order\Models\Order;
use Modules\Product\FrontendFilter\ShopFilter;
use Modules\Product\Models\ModelFilters\ProductFilter;
use Modules\Product\Support\CartesianProduct;
use Modules\Product\Traits\CustomFieldPriceTrait;

class Product extends Content
{

//    protected $dispatchesEvents = [
//        'updated' =>  ProductWasUpdated::class,
//     //   'updating' =>  ProductWasUpdated::class,
//      //  'updating' =>  ProductWasUpdated::class,
//     //   'deleted' => ProductWasUpdated::class,
//    ];


    /**
     * @method filter(array $filter)
     * @see ProductFilter
     */

    use CustomFieldPriceTrait;

    protected $table = 'content';

    protected $appends = ['price', 'qty', 'sku', 'content_data'];

    //  public $timestamps = false;

    public $fillable = [
        "subtype",
        "subtype_value",
        "content_type",
        "parent",
        "layout_file",
        "active_site_template",
        "title",
        "url",
        "content_meta_title",
        "content",
        "description",
        "content_body",
        "content_meta_keywords",
        "original_link",
        "require_login",
        "created_by",
        "is_home",
        "is_shop",
        "is_active",
        "updated_at",
        "created_at",
    ];


    public static $customFields = [
        [
            'type' => 'price',
            'name' => 'Price',
            'value' => [0]
        ]
    ];

    public static $contentDataDefault = [
        'qty' => 'nolimit',
        'sku' => '',
        'barcode' => '',
        'track_quantity' => '',
        'max_quantity_per_order' => '',
        'sell_oos' => '',
        'physical_product' => '',
        'free_shipping' => '',
        'shipping_fixed_cost' => '',
        'weight_type' => 'kg',
        'params_in_checkout' => 0,
        'has_special_price' => 0,
        'weight' => '',
        'width' => '',
        'height' => '',
        'depth' => ''
    ];

    public $sortable = [
        'id' => [
            'title' => 'Product'
        ]
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['content_type'] = 'product';
        $this->attributes['subtype'] = 'product';
    }

    public function modelFilter()
    {
        return $this->provideFilter(ProductFilter::class);
    }
}
