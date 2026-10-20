<?php

declare(strict_types=1);

namespace Arcanedev\Support\Middleware;

use Closure;
use Illuminate\Http\{JsonResponse, Request, Response};

/**
 * Class     VerifyJsonRequest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class VerifyJsonRequest
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Supported request method verbs.
     *
     * @var array
     */
    protected $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  string|array|null         $methods
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $methods = null)
    {
        if ($this->isJsonRequestValid($request, $methods)) {
            return $next($request);
        }

        return $this->jsonErrorResponse();
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Validate json Request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|array|null         $methods
     *
     * @return bool
     */
    protected function isJsonRequestValid(Request $request, $methods)
    {
        $methods = $this->getMethods($methods);

        if ( ! in_array($request->method(), $methods)) {
            return false;
        }

        return $request->isJson();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the error as json response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorResponse()
    {
        $data = [
            'status'  => 'error',
            'code'    => $statusCode = Response::HTTP_BAD_REQUEST,
            'message' => 'Request must be JSON',
        ];

        return new JsonResponse($data, $statusCode);
    }

    /**
     * Get request methods.
     *
     * @param  string|array|null  $methods
     *
     * @return array
     */
    protected function getMethods($methods): array
    {
        $methods = $methods ?? $this->methods;

        if (is_string($methods)) {
            $methods = (array) $methods;
        }

        return is_array($methods) ? array_map('strtoupper', $methods) : [];
    }
}
