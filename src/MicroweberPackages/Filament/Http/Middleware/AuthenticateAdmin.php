<?php

namespace MicroweberPackages\Filament\Http\Middleware;


use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin extends Middleware
{


    public function handle($request, \Closure $next, ...$guards)
    {


        if (!mw_is_installed()) {
            //
            return redirect('/')->with('error', 'Microweber is not installed.');
        }


        if (is_admin()) {

            return $next($request);
        }

        if (is_logged()) {
            // Return 403 Forbidden for non-admin users trying to access admin routes
            abort(403, 'Unauthorized action.');
        }


        return redirect($this->redirectTo($request));

    }


    protected function redirectTo($request): string
    {
        //    return route('filament.auth.login');
        return admin_url('login');
    }
}
