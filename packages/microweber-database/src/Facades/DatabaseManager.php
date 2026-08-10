<?php

namespace MicroweberPackages\Database\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Database\DatabaseManagerService;

/**
 * DatabaseManager facade — greppable public API for the database manager.
 *
 * @see \MicroweberPackages\Database\DatabaseManagerService
 * @mixin \MicroweberPackages\Database\DatabaseManagerService
 */
class DatabaseManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DatabaseManagerService::class;
    }
}
