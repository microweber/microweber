<?php
namespace MicroweberPackages\CustomField\Listeners;

class AddCustomFieldProductListener
{
    public function handle($event)
    {
        $request = $event->getRequest();
        $product = $event->getModel();

        $product->setCustomField(
            [
                'type' => 'price',
                'name' => 'Price',
                'value' => [$request['price']]
            ]
        );
        $product->save();

    }
}
