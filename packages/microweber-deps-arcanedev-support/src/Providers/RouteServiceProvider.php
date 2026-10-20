<?php

declare(strict_types=1);

namespace Arcanedev\Support\Providers;

use Arcanedev\Support\Routing\Concerns\RegistersRouteClasses;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as IlluminateServiceProvider;

/**
 * Class     RouteServiceProvider
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class RouteServiceProvider extends IlluminateServiceProvider
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use RegistersRouteClasses;
}
