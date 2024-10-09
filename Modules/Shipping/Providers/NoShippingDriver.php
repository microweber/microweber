<?php

namespace Modules\Shipping\Providers;

/**
 * @deprecated This class is deprecated.
 */
class NoShippingDriver extends AbstractShippingDriver implements ShippingDriverInterface
{

    public function isEnabled()
    {
        return true;
    }

    public function title()
    {
        return 'Other';
    }

    public function cost()
    {
        return 0;
    }

    public function process()
    {
        return [];
    }

    public function quickSetup()
    {
        return '';
    }
}
