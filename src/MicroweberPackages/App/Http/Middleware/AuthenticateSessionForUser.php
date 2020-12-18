<?php
namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Session\Middleware\AuthenticateSession;


class AuthenticateSessionForUser extends AuthenticateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
         if (!mw_is_installed()) {
            return $next($request);
        }


        return parent::handle($request,$next);

    }
}


