<?php

namespace MicroweberPackages\Filament\Http\Middleware;


use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin extends Middleware
{


    public function handle($request, \Closure $next, ...$guards)
    {

        if (is_admin()) {
            return $next($request);
        }

        return redirect($this->redirectTo($request));

    }


    protected function redirectTo($request): string
    {
        //    return route('filament.auth.login');
        return admin_url('login');
    }
}
