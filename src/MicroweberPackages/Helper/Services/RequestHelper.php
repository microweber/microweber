<?php

namespace MicroweberPackages\Helper\Services;

use Illuminate\Support\Facades\Request;

/**
 * Request Helper Service
 *
 * Provides backward-compatible access to request data
 * while transitioning from superglobals to Laravel's Request facade.
 *
 * @deprecated This class is for backward compatibility during migration.
 *             Use Laravel's Request facade directly in new code.
 */
class RequestHelper
{
    /**
     * Get all request data (GET and POST merged)
     *
     * @return array
     */
    public static function all(): array
    {
        return Request::all();
    }

    /**
     * Get a specific value from the request
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Request::input($key, $default);
    }

    /**
     * Check if a key exists in the request
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return Request::has($key);
    }

    /**
     * Get only specific keys from the request
     *
     * @param array|string $keys
     * @return array
     */
    public static function only($keys): array
    {
        return Request::only($keys);
    }

    /**
     * Check if the request has any of the given keys
     *
     * @param array|string $keys
     * @return bool
     */
    public static function hasAny($keys): bool
    {
        return Request::hasAny($keys);
    }

    /**
     * Get request data as array, replacing superglobal usage
     *
     * @return array
     */
    public static function getRequestData(): array
    {
        return Request::all();
    }
}
