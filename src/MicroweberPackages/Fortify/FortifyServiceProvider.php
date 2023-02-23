<?php

namespace MicroweberPackages\Fortify;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use MicroweberPackages\Fortify\Actions\Fortify\CreateNewUser;
use MicroweberPackages\Fortify\Actions\Fortify\ResetUserPassword;
use MicroweberPackages\Fortify\Actions\Fortify\UpdateUserPassword;
use MicroweberPackages\Fortify\Actions\Fortify\UpdateUserProfileInformation;
use MicroweberPackages\User\Http\Controllers\UserLoginController;
use MicroweberPackages\User\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->register(\Laravel\Fortify\FortifyServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
        $this->mergeConfigFrom(__DIR__ . '/config/fortify.php', 'fortify');

        View::addNamespace('fortify', __DIR__.'/resources/views');

//        Fortify::createUsersUsing(CreateNewUser::class);
//        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
//        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
//        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::twoFactorChallengeView(function () {
            return view('fortify::two-factor-challenge');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
