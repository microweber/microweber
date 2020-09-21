<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Product\Http\Controllers\Admin;

use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Product\Http\Requests\ProductRequest;
use MicroweberPackages\Product\Product;

class ProductsController
{
    use HasCrudActions;

    public $model = Product::class;
    public $validator = ProductRequest::class;


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $fromPost = $this->getRequest('update')->all();

        if (!isset($fromPost['track_quantity'])) {
            $fromPost['track_quantity'] = 0;
        }

        $product = Product::find($fromPost['id']);
        $product->update($fromPost);
        $product->setContentData([
            'price' => $fromPost['price'],
            'special_price' => $fromPost['special_price'],
            'sku' => $fromPost['sku'],
            'barcode' => $fromPost['barcode'],
            'quantity' => $fromPost['quantity'],
            'track_quantity' => $fromPost['track_quantity'],
            'max_quantity_per_order' => $fromPost['max_quantity_per_order']
        ]);
        $product->save();

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($product);
        }

        return $product->id;
    }
}