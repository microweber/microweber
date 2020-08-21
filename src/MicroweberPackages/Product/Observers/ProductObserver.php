<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Product\Observers;

use MicroweberPackages\Product\Product;

class ProductObserver
{
    /**
     * Handle the Page "saving" event.
     *
     * @param  \MicroweberPackages\Product\Product  $product
     * @return void
     */
    public function saving(Product $product)
    {
        $product->content_type = 'product';
        
        cache_delete('content');
    }
}