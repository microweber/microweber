<?php

namespace MicroweberPackages\Shipping\Providers;


class NoShippingProvider implements ShippingProviderInterface
{

    public function title()
    {
        return '';
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