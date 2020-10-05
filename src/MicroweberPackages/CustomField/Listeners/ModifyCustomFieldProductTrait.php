<?php

namespace MicroweberPackages\CustomField\Listeners;

trait ModifyCustomFieldProductTrait
{
    public function handle($event)
    {
        $data = $event->getData();
        $product = $event->getModel();

        if (isset($data['price'])) {
            $product->setCustomField(
                [
                    'type' => 'price',
                    'name' => 'Price',
                    'value' => [$data['price']]
                ]
            );
            $product->save();
        }
    }
}
