<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/1/2020
 * Time: 2:04 PM
 */

namespace MicroweberPackages\ContentData\Listeners;

use MicroweberPackages\Product\Product;

trait ModifyContentDataProductTrait
{
    public function handle($event)
    {
        $request = $event->getRequest();
        $product = $event->getModel();

        $contentDataDefault = Product::$contentDataDefault;
        $contentDataFromPost = $contentDataDefault;
        foreach ($request as $keyPost=>$keyValue) {
            if (isset($contentDataDefault[$keyPost])) {
                $contentDataFromPost[$keyPost] = $keyValue;
            }
        }

        $product->setContentData($contentDataFromPost);
        $product->save();

    }
}