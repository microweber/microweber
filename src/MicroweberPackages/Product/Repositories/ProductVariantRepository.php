<?php

namespace MicroweberPackages\Product\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Product\Models\ProductVariant;

class ProductVariantRepository extends BaseRepository
{

    public function __construct(ProductVariant $product)
    {
        $this->model = $product;
    }

    public function create($data)
    {
        $product = $this->model->create($data);

        return $product;
    }

    public function update($data, $id)
    {
        $product = $this->model->find($id);

        $product->update($data);

        return $product;
    }


    public function delete($id)
    {
        $product = $this->model->find($id);

        return $product->delete();
    }


    public function destroy($ids)
    {
        return $this->model->destroy($ids);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

}
