<?php

declare(strict_types=1);

if ( ! function_exists('laravel_version')) {
    /**
     * Get laravel version or check if the same version
     *
     * @param  string|null $version
     *
     * @return string|bool
     */
    function laravel_version(string $version = null) {
        $appVersion = app()->version();

        if (is_null($version)) {
            return $appVersion;
        }

        return substr($appVersion, 0, strlen($version)) === $version;
    }
}

if ( ! function_exists('route_is')) {
    /**
     * Check if route(s) is the current route.
     *
     * @param  array|string  $routes
     *
     * @return bool
     */
    function route_is($routes): bool
    {
        if ( ! is_array($routes)) {
            $routes = [$routes];
        }

        /** @var Illuminate\Routing\Router $router */
        $router = app('router');

        return call_user_func_array([$router, 'is'], $routes);
    }
}
