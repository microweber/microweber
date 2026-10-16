<?php

namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
/**
 * @deprecated
 */
class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
