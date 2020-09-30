<?php
namespace MicroweberPackages\CustomField\Listeners;

class AddCustomFieldProductListener
{
    public function handle($event)
    {
        $request = $event->getRequest();
        $product = $event->getEntity();

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
