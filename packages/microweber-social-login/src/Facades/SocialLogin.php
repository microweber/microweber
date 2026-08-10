<?php

namespace MicroweberPackages\SocialLogin\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;

/**
 * SocialLogin facade — greppable public API for social login.
 *
 * @see \MicroweberPackages\SocialLogin\Services\SocialLoginService
 * @mixin \MicroweberPackages\SocialLogin\Services\SocialLoginService
 */
class SocialLogin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SocialLoginServiceContract::class;
    }
}
