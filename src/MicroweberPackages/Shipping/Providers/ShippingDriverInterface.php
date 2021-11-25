<?php

namespace MicroweberPackages\Shipping\Providers;


interface ShippingDriverInterface
{
    public function title();
    public function quickSetup();


//    public function cost($params=[]);
//    public function process($params=[]);
//    public function display();
//    public function admin();
}
