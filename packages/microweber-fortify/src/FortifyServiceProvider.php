<?php

namespace MicroweberPackages\Fortify;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use MicroweberPackages\Fortify\Actions\CreateNewUser;
use MicroweberPackages\Fortify\Actions\ResetUserPassword;
use MicroweberPackages\Fortify\Actions\UpdateUserPassword;
use MicroweberPackages\Fortify\Actions\UpdateUserProfileInformation;
use MicroweberPackages\Fortify\Http\Livewire\TwoFactorSetupComponent;
use MicroweberPackages\Fortify\Http\Livewire\TwoFactorChallengeComponent;
use MicroweberPackages\Fortify\Http\Middleware\RequireTwoFactor;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fortify.php', 'fortify');
        $this->mergeConfigFrom(__DIR__ . '/../config/microweber-fortify.php', 'microweber-fortify');

        // Register the Fortify base service provider if not already registered
        $isRegistered = false;
        foreach ($this->app->getLoadedProviders() as $provider => $loaded) {
            if ($provider === \Laravel\Fortify\FortifyServiceProvider::class) {
                $isRegistered = true;
                break;
            }
        }
        if (!$isRegistered) {
            $this->app->register(\Laravel\Fortify\FortifyServiceProvider::class);
        }
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'microweber-fortify');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        View::addNamespace('fortify', __DIR__ . '/../resources/views');

        $this->publishes([
            __DIR__ . '/../config/fortify.php' => config_path('fortify.php'),
            __DIR__ . '/../config/microweber-fortify.php' => config_path('microweber-fortify.php'),
        ], 'microweber-fortify-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/microweber-fortify'),
        ], 'microweber-fortify-views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'microweber-fortify-migrations');

        // Register Livewire components
        if (class_exists(Livewire::class)) {
            Livewire::component('two-factor-setup', TwoFactorSetupComponent::class);
            Livewire::component('two-factor-challenge', TwoFactorChallengeComponent::class);
        }

        // Register middleware alias
        $router = $this->app['router'];
        $router->aliasMiddleware('require-2fa', RequireTwoFactor::class);

        $this->configureFortify();
        $this->configureRateLimiting();
    }

    protected function configureFortify(): void
    {
        Fortify::twoFactorChallengeView(function () {
            return view('microweber-fortify::two-factor-challenge');
        });

        Fortify::resetPasswordView(function (Request $request) {
            // Try Microweber view first, fall back to package view
            if (view()->exists('user::auth.reset-password')) {
                return view('user::auth.reset-password', [
                    'email' => $request->email,
                    'token' => $request->token,
                ]);
            }
            return view('microweber-fortify::auth.reset-password', [
                'email' => $request->email,
                'token' => $request->token,
            ]);
        });

        Fortify::authenticateUsing(function (Request $request) {
            $userModel = config('microweber-fortify.user_model', config('auth.providers.users.model'));
            $usernameField = config('fortify.username', 'email');

            $user = $userModel::where($usernameField, $request->input($usernameField))->first();

            if (!$user && $usernameField === 'username' && $request->has('email')) {
                $user = $userModel::where('email', $request->email)->first();
            }

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $key = (string) ($request->email ?: $request->username ?: '');
            return Limit::perMinutes(15, 5)->by($key . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinutes(15, 5)->by(
                (string) $request->session()->get('login.id', $request->ip())
            );
        });
    }
}