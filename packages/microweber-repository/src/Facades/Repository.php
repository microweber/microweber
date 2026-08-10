<?php

namespace MicroweberPackages\Repository\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Repository\RepositoryManager;

/**
 * Repository facade — greppable public API for repository manager.
 *
 * @see \MicroweberPackages\Repository\RepositoryManager
 * @mixin \MicroweberPackages\Repository\RepositoryManager
 */
class Repository extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RepositoryManager::class;
    }
}
