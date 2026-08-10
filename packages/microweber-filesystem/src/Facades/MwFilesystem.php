<?php

namespace MicroweberPackages\Filesystem\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Filesystem\FilesystemService;

/**
 * MwFilesystem facade — greppable public API for Microweber filesystem helpers.
 *
 * @see \MicroweberPackages\Filesystem\FilesystemService
 * @mixin \MicroweberPackages\Filesystem\FilesystemService
 */
class MwFilesystem extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FilesystemService::class;
    }
}
