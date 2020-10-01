<?php
namespace MicroweberPackages\Menu\Listeners;


class EditMenuPageListener
{
    public function handle($event)
    {
        $request = $event->getRequest();
        $product = $event->getModel();


    }
}
