<?php
namespace MicroweberPackages\Menu\Listeners;

class AddMenuPageListener
{
    public function handle($event)
    {
        $request = $event->getRequest();
        $product = $event->getModel();


    }
}
