<?php



namespace MicroweberPackages\Shipping\Providers;


abstract class AbstractShippingDriver
{
    public function isEnabled()
    {
        return true;
    }

    public function enable()
    {
        return true;
    }

    public function disable()
    {
        return true;
    }

    public function title()
    {
        return 'Example provider';
    }

    public function cost()
    {
        return 0;
    }

    public function validate($data = [])
    {
        return [];
    }

    public function process()
    {
        return [];
    }

}
