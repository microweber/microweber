<?php namespace Microweber\App\Http\Middleware;

use Closure;


use Illuminate\Session\Middleware\StartSession as BaseStartSession;

// from http://stackoverflow.com/a/29251516/731166
class SessionlessMiddleware extends BaseStartSession {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        \Config::set('session.driver', 'array');

        return parent::handle($request, $next);
    }
}

