<?php

namespace MicroweberPackages\Shipping\Drivers;


interface ShippingDriverInterface
{
  //  public function title();
    public function cost();
    public function process();
//    public function display();
//    public function admin();
}