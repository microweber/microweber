<?php

namespace MicroweberPackages\Shipping\Providers;


interface ShippingProviderInterface
{
    public function title();
    public function cost();
    public function process();
//    public function display();
//    public function admin();
}