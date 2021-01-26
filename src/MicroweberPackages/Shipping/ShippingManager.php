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

use DB;
use Illuminate\Support\Manager;
use MicroweberPackages\Shipping\Drivers\PickupFromOffice;


/**
 * ShippingManager module api.
 */
// ------------------------------------------------------------------------

class ShippingManager extends Manager
{

    //@todo
    public function getDefaultDriver()
    {

       // return new PickupFromOffice();
        throw new \InvalidArgumentException('No Shipping driver was specified.');
    }

    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

}
