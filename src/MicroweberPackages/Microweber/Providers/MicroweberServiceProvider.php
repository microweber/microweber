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

namespace MicroweberPackages\Microweber\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Microweber\Microweber;

class MicroweberServiceProvider extends ServiceProvider
{

    public function register() : void
    {
        /**
         * @property Microweber $microweber
         */
        $this->app->singleton('microweber', function () {
            return new Microweber();
        });

    }
}
