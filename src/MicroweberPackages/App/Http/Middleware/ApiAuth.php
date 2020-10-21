<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * The routes that should be excluded from verification.
     *
     * @var array
     */
    protected $except = [

    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() &&  Auth::user()->is_admin == 1) {
            return $next($request);
        }

        if ($this->inExceptArray($request) || Auth::check()) {
            return $next($request);
        }

        throw new \Exception('Api unauthorized');
    }


    /**
     * Determine if the request URI is in except array.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            $routeName = optional($request->route())->getName();

            if (preg_match("/{$except}/", $routeName)) {
                return true;
            }
        }

        return false;
    }
}