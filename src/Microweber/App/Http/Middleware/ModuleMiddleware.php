<?php
namespace Microweber\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class ModuleMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

}