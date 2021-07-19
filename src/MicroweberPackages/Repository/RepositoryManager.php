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

namespace MicroweberPackages\Repository;


use Illuminate\Support\Manager;
use MicroweberPackages\Core\Repositories\BaseRepositoryInterface;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


/**
 * @mixin AbstractRepository
 */
class RepositoryManager extends Manager
{

    // public $defaultDriver = 'NoShippingDriver';

    /**
     * Get default driver instance.
     *
     * @return AbstractRepository
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
         return;
    }

//    public function createDefaultDriver()
//    {
//        return $this->getDefaultDriver();
//    }


    /**
     * Get a driver instance.
     *
     * @param  string|null $driver
     * @return BaseRepositoryInterface
     *
     * @throws \InvalidArgumentException
     */
//    public function driver($driver = 'default')
//    {
//        return parent::driver($driver);
//    }



//    public function with($driver = null)
//    {
//        return $this->driver($driver);
//    }

}
