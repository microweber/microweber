<?php

namespace MicroweberPackages\Filament\Http\Middleware;


use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards): void
    {

        abort_if(!is_admin(), 403, 'You are not allowed to access this page');

    }

    protected function redirectTo($request): string
    {
        return route('filament.auth.login');
    }
}
