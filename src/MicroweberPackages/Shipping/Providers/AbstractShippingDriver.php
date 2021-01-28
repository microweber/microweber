<?php



namespace MicroweberPackages\Shipping\Providers;


abstract class AbstractShippingDriver
{
    public function title()
    {
        return 'Example provider';
    }

    public function cost()
    {
        return 0;
    }

    public function process()
    {
        return [];
    }

}