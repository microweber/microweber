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

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function create($data)
    {
        event($event = new ProductIsCreating($data));

        $product = $this->model->create($data);

        event(new ProductWasCreated($data, $product));


        return $product->id;
    }

    public function update($data, $id)
    {
        $product = $this->model->find($id);

        event($event = new ProductIsUpdating($data, $product));

        $product->update($data);

        event(new ProductWasUpdated($data, $product));

        return $this->model->id;
    }


    public function destroy($ids)
    {
        $product = $this->model->find($id);

        event(new ProductWasDeleted($product));

        return $product->delete();
    }


    public function find($id)
    {
        return $this->model->find($id);
    }

}
