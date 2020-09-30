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
use MicroweberPackages\Product\Repositories\ProductRepository;

class ProductsController
{
    use HasCrudActions;

    public $model = Product::class;
    public $repository = ProductRepository::class;
    public $validator = ProductRequest::class;

    /*

    public function storeDEPRECATED()
    {
        $fromPost = $this->getRequest('store')->all();
        unset($fromPost['id']);

        $contentDataDefault = Product::$contentDataDefault;
        $contentDataFromPost = $contentDataDefault;
        foreach ($fromPost as $keyPost=>$keyValue) {
            if (isset($contentDataDefault[$keyPost])) {
                $contentDataFromPost[$keyPost] = $keyValue;
            }
        }

        $product = new Product();
        $product = $product->create($fromPost);

        if (!$product) {
            return false;
        }

        $product = Product::find($product->id);
        $product->additionalData = $fromPost;

        if (isset($fromPost['price'])) {
            $product->setCustomField(
                [
                    'type' => 'price',
                    'name' => 'Price',
                    'value' => [$fromPost['price']]
                ]
            );
        }
        $product->setContentData($contentDataFromPost);

        if (isset($fromPost['categories'])) {
            $categories = explode(',',$fromPost['categories']);
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $product->addToCategory($category);
                }
            }
        }

        $product->save();



        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($product);
        }

        return $product->id;
    }

    public function updateDEPRECATED()
    {
        $fromPost = $this->getRequest('update')->all();

        $contentDataDefault = Product::$contentDataDefault;
        $contentDataFromPost = $contentDataDefault;
        foreach ($fromPost as $keyPost=>$keyValue) {
            if (isset($contentDataDefault[$keyPost])) {
                $contentDataFromPost[$keyPost] = $keyValue;
            }
        }

        $product = Product::find($fromPost['id']);
        $product->additionalData = $fromPost;

        $product->update($fromPost);

        if (isset($fromPost['price'])) {
            $product->setCustomField(
                [
                    'type' => 'price',
                    'name' => 'Price',
                    'value' => [$fromPost['price']]
                ]
            );
        }

        $product->setContentData($contentDataFromPost);
        $product->save();

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($product);
        }

        return $product->id;
    }*/
}