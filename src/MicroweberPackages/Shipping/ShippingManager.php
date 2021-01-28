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
use MicroweberPackages\Shipping\Providers\NoShippingProvider;
use MicroweberPackages\Shipping\Providers\PickupFromOffice;


/**
 * ShippingManager module api.
 */
// ------------------------------------------------------------------------

class ShippingManager extends Manager
{

    //@todo
    public function getDefaultDriver()
    {

         return new NoShippingProvider();
        // return new PickupFromOffice();
      //  throw new \InvalidArgumentException('No Shipping driver was specified.');
    }

    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver=null)
    {
        return $this->driver($driver);
    }

}
