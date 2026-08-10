<?php

namespace MicroweberPackages\CdnSync\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\CdnSync\Services\CdnSyncService;

/**
 * CdnSync facade — greppable public API for CDN sync.
 *
 * @see \MicroweberPackages\CdnSync\Services\CdnSyncService
 * @mixin \MicroweberPackages\CdnSync\Services\CdnSyncService
 */
class CdnSync extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CdnSyncService::class;
    }
}
