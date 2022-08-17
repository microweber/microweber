<?php

namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Support\Str;

use Closure;
use Illuminate\Http\Request;

class IsAjaxMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!is_ajax()) {
            $error = 'You only allowed to make ajax requests';
            return abort(403, $error);
        }
        return $next($request);

    }




}

