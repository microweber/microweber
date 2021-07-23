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


/**
 * @mixin BaseRepository
 */
class RepositoryManager extends Manager
{


    /**
     * Get default driver instance.
     *
     * @return BaseRepository
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
         return;
    }





}
