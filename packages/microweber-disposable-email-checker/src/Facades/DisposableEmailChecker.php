<?php

namespace MicroweberPackages\DisposableEmailChecker\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;

/**
 * DisposableEmailChecker facade — greppable public API for disposable email checks.
 *
 * @see \MicroweberPackages\DisposableEmailChecker\Services\DisposableEmailCheckerService
 * @mixin \MicroweberPackages\DisposableEmailChecker\Services\DisposableEmailCheckerService
 */
class DisposableEmailChecker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DisposableEmailCheckerContract::class;
    }
}
