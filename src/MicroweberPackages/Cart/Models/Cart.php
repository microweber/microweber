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

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Product\Models\Product;

class Cart extends Model
{
    public $table = 'cart';


    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'rel_id');
    }
}
