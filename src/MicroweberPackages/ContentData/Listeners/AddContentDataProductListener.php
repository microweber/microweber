<?php
namespace MicroweberPackages\ContentData\Listeners;

use MicroweberPackages\Product\Product;

class AddContentDataProductListener
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
