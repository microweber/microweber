<?php
namespace MicroweberPackages\Category\Listeners;

class CategoryListener
{
    public function handle($event)
    {
        $data = $event->getData();
    }
}
