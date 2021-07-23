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
use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Core\Repositories\BaseRepositoryInterface;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


/**
 * @mixin AbstractRepository
 */
class RepositoryManager extends Manager
{


    /**
     * Get a driver instance.
     *
     * @param  string|null  $driver
     * @return AbstractRepository
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = null)
    {
        return parent::driver($driver);
    }


    /**
     * Get default driver instance.
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
         return;
    }





}
