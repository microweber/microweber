<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\User\Providers;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\User\Filament\Pages\AdminProfileRedirectPage;
use Livewire\Livewire;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\User\Http\Livewire\Admin\CreateProfileInformationForm;
use MicroweberPackages\User\Http\Livewire\Admin\DeleteUserForm;
use MicroweberPackages\User\Http\Livewire\Admin\LoginAsUserForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdatePasswordForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdatePasswordWithoutConfirmFormModal;
use MicroweberPackages\User\Http\Livewire\Admin\UpdateProfileInformationForm;
use MicroweberPackages\User\Http\Livewire\Admin\UpdateStatusAndRoleForm;
use MicroweberPackages\User\Http\Livewire\Admin\UserLoginAttemptsModal;
use MicroweberPackages\User\Http\Livewire\Admin\UsersList;
use MicroweberPackages\User\Http\Livewire\Admin\UserTosLogModal;
use MicroweberPackages\User\Http\Livewire\LogoutOtherBrowserSessionsForm;
use MicroweberPackages\User\Http\Livewire\TwoFactorAuthenticationForm;
use MicroweberPackages\User\Services\UserManager;


class UserServiceProvider extends AuthServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\MicroweberPackages\User\Providers\AuthServiceProvider::class);

        // Register the Passport package in the REGISTER phase (not boot) so its
        // /api/passport/* routes load before AppServiceProvider::boot() loads the
        // greedy api/{all} catch-all in App/routes/web.php — otherwise the
        // catch-all (registered first) swallows them. Mirrors how this provider
        // loads its own routes in register() below.
        $this->app->register(\MicroweberPackages\Passport\Providers\MicroweberPassportServiceProvider::class);
        $this->app->singleton('user_manager', function ($app) {
            return new UserManager();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__. '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__. '/../database/migrations/');
        $this->loadViewsFrom( __DIR__ . '/../resources/views/components', 'user');

        View::addNamespace('user', __DIR__ . '/../resources/views');
        View::addNamespace('admin', __DIR__ . '/../resources/views/admin');

        // Livewire v4 treats '::' as namespace separators in the Finder,
        // so we register a fallback resolver for components with '::' names.
        $userComponents = [
            'admin::users-list' => UsersList::class,
            'admin::users.create-profile-information-form' => CreateProfileInformationForm::class,
            'admin::edit-user.update-profile-form' => UpdateProfileInformationForm::class,
            'admin::edit-user.update-status-and-role-form' => UpdateStatusAndRoleForm::class,
            'admin::edit-user.update-password-form' => UpdatePasswordForm::class,
            'admin::edit-user.update-password-without-confirm-form-modal' => UpdatePasswordWithoutConfirmFormModal::class,
            'admin::edit-user.two-factor-authentication-form' => \MicroweberPackages\User\Http\Livewire\Admin\TwoFactorAuthenticationForm::class,
            'admin::edit-user.logout-other-browser-sessions-form' => \MicroweberPackages\User\Http\Livewire\Admin\LogoutOtherBrowserSessionsForm::class,
            'admin::edit-user.delete-user-form' => DeleteUserForm::class,
            'admin::edit-user.login-as-user-form' => LoginAsUserForm::class,
            'admin::user-tos-log' => UserTosLogModal::class,
            'admin::user-login-attempts' => UserLoginAttemptsModal::class,
            'user::profile.two-factor-authentication-form' => TwoFactorAuthenticationForm::class,
            'user::profile.logout-other-browser-sessions-form' => LogoutOtherBrowserSessionsForm::class,
        ];

        foreach ($userComponents as $name => $class) {
            Livewire::component($name, $class);
        }

        Livewire::resolveMissingComponent(function (string $name) use ($userComponents) {
            return $userComponents[$name] ?? null;
        });


        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);

        // Passport Filament pages are now registered by the microweber-passport package.
        // No need to register ApiApplicationsPage here.

        // task-2026-06-06-AI839 — make /admin/profile resolve (it redirects to
        // the signed-in user's edit page) instead of falling through to 404.
        FilamentRegistry::registerPage(AdminProfileRedirectPage::class);

        // Register the social login package first (provides the service),
        // then the CMS-specific provider that populates its config from options.
        $this->app->register(\MicroweberPackages\SocialLogin\Providers\SocialLoginServiceProvider::class);
        $this->app->register(\MicroweberPackages\User\Providers\UserSocialiteServiceProvider::class);

        // Register the disposable email checker package.
        // The checker is initialised only when the admin has enabled the option.
        $this->app->register(\MicroweberPackages\DisposableEmailChecker\Providers\DisposableEmailCheckerServiceProvider::class);
        $this->app->booted(function () {
            // The Filament toggle stores '1' when on (empty when off) — see
            // AdminSettingsPage::updated(); this is the only writer of the option.
            $enabled = get_option('disable_registration_with_temporary_email', 'users') == 1;
            config()->set('disposable-email-checker.enabled', $enabled);
        });

    }

    public function registerMenu()
    {
    /*    AdminManager::getMenuInstance('left_menu_top')->addChild('Users', [
            'uri' => admin_url('users'),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M400 576q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80 896V784q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404 696h-4q-71 0-127.5 18T180 750q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584 852l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628 596l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732 876l-12 60h-80Zm40-120q33 0 56.5-23.5T760 736q0-33-23.5-56.5T680 656q-33 0-56.5 23.5T600 736q0 33 23.5 56.5T680 816ZM400 496q33 0 56.5-23.5T480 416q0-33-23.5-56.5T400 336q-33 0-56.5 23.5T320 416q0 33 23.5 56.5T400 496Zm0-80Zm12 400Z"/></svg>'
            ]
        ]);

        AdminManager::getMenuInstance('left_menu_top')
            ->menuItems
            ->getChild('Users')
            ->setExtra('orderNumber', 6);*/
    }

    public function boot()
    {

        /**
         * @property \MicroweberPackages\User\Services\UserManager $user_manager
         */

        $this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // Register Validators
        Validator::extendImplicit(
            'terms',
            'MicroweberPackages\User\Validators\TermsValidator@validate',
            'Terms are not accepted');
        Validator::extendImplicit(
            'temporary_email_check',
            'MicroweberPackages\User\Validators\TemporaryEmailCheckValidator@validate',
            'You cannot register with email from this domain.');

    }

    /**
     * Passport scopes for the /api/module/* headless API.
     *
     * Delegates to the microweber-passport package config. Kept here as a
     * convenience accessor for CMS code that references this method.
     *
     * @return array<string, string>
     */
    public static function headlessApiScopes(): array
    {
        return config('microweber-passport.scopes', []);
    }
}
