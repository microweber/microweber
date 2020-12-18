<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/1/2020
 * Time: 2:04 PM
 */

namespace MicroweberPackages\ContentData\Listeners;

use MicroweberPackages\Product\Models\Product;

trait ModifyContentDataProductTrait
{
    public function handle($event)
    {
        $data = $event->getData();
        $product = $event->getModel();

        $contentDataDefault = Product::$contentDataDefault;
        $contentDataFromPost = $contentDataDefault;
        foreach ($data as $dataKey=>$dataValue) {
            if (isset($contentDataDefault[$dataKey])) {
                $contentDataFromPost[$dataKey] = $dataValue;
            }
        }

        $product->setContentData($contentDataFromPost);
        $product->save();

    }
}