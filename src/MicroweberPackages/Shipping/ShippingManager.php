<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Shipping;


use Illuminate\Support\Manager;
use MicroweberPackages\Shipping\Providers\AbstractShippingDriver;
use MicroweberPackages\Shipping\Providers\NoShippingDriver;


/**
 * ShippingManager module api.
 */
// ------------------------------------------------------------------------


/**
 * @mixin AbstractShippingDriver
 */
class ShippingManager extends Manager
{

    /**
     * Get default driver instance.
     *
     * @return AbstractShippingDriver
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {

        return new NoShippingDriver();
        // return new PickupFromOffice();
        //  throw new \InvalidArgumentException('No Shipping driver was specified.');
    }

    public function createDefaultDriver()
    {
        return $this->getDefaultDriver();
    }



    /**
     * Get a driver instance.
     *
     * @param  string|null  $driver
     * @return AbstractShippingDriver
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = 'default')
    {
         return parent::driver($driver);
    }

//    public function with($driver = null)
//    {
//        return $this->driver($driver);
//    }

}
