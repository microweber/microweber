<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;


class ThrottleExternalRequests extends ThrottleRequests
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  int|string $maxAttempts
     * @param  float|int $decayMinutes
     * @param  string $prefix
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
         if ($request->instance()) {
            $server = $request->instance()->server();
            if (isset($server["x-no-throttle"]) and $server["x-no-throttle"]) {
                return $next($request);
            }
        }

        try {
            $response =  parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);

        }
        catch( TooManyRequestsHttpException $e ) {
            return response()->json(['error'=>'Too many requests'], 429);
        }




        return $response;

    }

}
