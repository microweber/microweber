<?php

namespace MicroweberPackages\User\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as AuthServiceProviderBase;
use Illuminate\Support\Facades\Gate;

use MicroweberPackages\User\Models\User;

class AuthServiceProvider extends AuthServiceProviderBase
{


    /**
     * Register any application authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', function (User $user) {
            if ($user and intval($user->is_admin) == 1) {
                return true;
            }
        });

        Gate::define('isAuthor', function (User $user, Model $model) {
            if ($user and  $model->created_by == $user->id) {
                return true;
            }
        });
    }
}
