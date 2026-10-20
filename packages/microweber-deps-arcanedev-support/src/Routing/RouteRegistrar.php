<?php

declare(strict_types=1);

namespace Arcanedev\Support\Routing;

use Arcanedev\Support\Routing\Concerns\RegistersRouteClasses;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Router;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * Class     RouteRegistrar
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @method  \Illuminate\Routing\RouteRegistrar  bind(string $key, \Closure $binder)
 * @method  void                                map()
 * @method  void                                bindings()
 *
 * @mixin  \Illuminate\Routing\RouteRegistrar
 */
abstract class RouteRegistrar
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use RegistersRouteClasses,
        ForwardsCalls;

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Pass dynamic methods onto the router instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallToRouter(
            app(Router::class), $method, $parameters
        );
    }

    /**
     * Pass dynamic methods onto the router instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @param  string                                   $method
     * @param  array                                    $parameters
     *
     * @return mixed
     */
    protected function forwardCallToRouter(Registrar $router, $method, $parameters)
    {
        return $this->forwardCallTo($router, $method, $parameters);
    }
}
