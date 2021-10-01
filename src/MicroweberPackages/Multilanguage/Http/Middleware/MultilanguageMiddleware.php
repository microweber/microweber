<?php
/**
 * @author Bobi microweber
 */
namespace MicroweberPackages\Multilanguage\Http\Middleware;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MultilanguageMiddleware
{
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
