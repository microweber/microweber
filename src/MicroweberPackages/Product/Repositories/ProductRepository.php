<?php

namespace MicroweberPackages\Product\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Product\Events\ProductIsCreating;
use MicroweberPackages\Product\Events\ProductIsUpdating;
use MicroweberPackages\Product\Events\ProductWasCreated;
use MicroweberPackages\Product\Events\ProductWasDeleted;
use MicroweberPackages\Product\Events\ProductWasUpdated;
use MicroweberPackages\Product\Product;

class ProductRepository extends BaseRepository
{

    public function create($request)
    {
        event($event = new ProductIsCreating($request));

        $product = Product::create($request);

        event(new ProductWasCreated($product, $request));


        return $product;
    }

    public function update($product, $request)
    {
        event($event = new ProductIsUpdating($product, $request));

        $product->update($request);

        event(new ProductWasUpdated($product, $request));

        return $product;
    }


    public function destroy($product)
    {
        event(new ProductWasDeleted($product));

        return $product->delete();
    }

}
