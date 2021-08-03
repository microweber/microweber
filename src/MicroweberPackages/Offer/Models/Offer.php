<?php

namespace MicroweberPackages\Offer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Offer extends Model
{
    public $table = 'offers';

    public $fillable = [
        "product_id",
        "price_id",
        "offer_price",
        "created_at",
        "updated_at",
        "expires_at",
        "created_by",
        "edited_by",
        "is_active",
    ];

    /**
     * Get the product associated with the offer.
     */
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
