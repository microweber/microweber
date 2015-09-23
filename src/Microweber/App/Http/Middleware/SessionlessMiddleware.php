<?php namespace Microweber\App\Http\Middleware;

use Closure;


use Illuminate\Session\Middleware\StartSession as BaseStartSession;

class SessionlessMiddleware extends BaseStartSession
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
        \Config::set('session.driver', 'array');
        return parent::handle($request, $next);
    }
}

//
//class SessionlessMiddleware {
//
//    /**
//     * Handle an incoming request.
//     *
//     * @param  \Illuminate\Http\Request $request
//     * @param  \Closure                 $next
//     *
//     * @return mixed
//     */
//    public function handle($request, Closure $next) {
//
//
//        $r = null;
//        try {
//            \Config::set('session.driver', 'array');
//
//
//            return $next($request);
//
//        } catch (\Exception $e) {
//            return $next($request);
//
//        } catch (ErrorException $e) {
//            return $next($request);
//
//        }
//        return $next($request);
//        $r = $next($request);
//        return $r;
//
//
//    }
//
//}
