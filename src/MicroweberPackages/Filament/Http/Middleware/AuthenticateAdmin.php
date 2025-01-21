<?php

namespace MicroweberPackages\Filament\Http\Middleware;


use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin extends Middleware
{
    protected function authenticate($request, array $guards)
    {
         abort_if(!is_admin(), 403, 'You are not allowed to access this page, please login as admin');


//        if (!is_admin()) {
//            return $this->redirectTo($request);
//        }

    }

    protected function redirectTo($request): string
    {
        //    return route('filament.auth.login');
        return admin_url('login');
    }
}
