<?php

namespace MicroweberPackages\PhpQuery\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\PhpQuery\PhpQueryManager;

/**
 * PhpQuery facade — greppable public API for PHPQuery HTML processing.
 *
 * @see \MicroweberPackages\PhpQuery\PhpQueryManager
 * @mixin \MicroweberPackages\PhpQuery\PhpQueryManager
 */
class PhpQuery extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PhpQueryManager::class;
    }
}
