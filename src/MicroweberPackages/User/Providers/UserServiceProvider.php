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
use Laravel\Passport\Passport;
use Livewire\Livewire;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Admin\Facades\AdminManager;
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
use MicroweberPackages\User\Services\RSAKeys;
use MicroweberPackages\User\UserManager;



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


        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__. '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__. '/../database/migrations/');
        $this->loadViewsFrom( __DIR__ . '/../resources/views/components', 'user');

        View::addNamespace('user', __DIR__ . '/../resources/views');
        View::addNamespace('admin', __DIR__ . '/../resources/views/admin');

        Livewire::component('admin::users-list', UsersList::class);
        Livewire::component('admin::users.create-profile-information-form', CreateProfileInformationForm::class);
        Livewire::component('admin::edit-user.update-profile-form', UpdateProfileInformationForm::class);
        Livewire::component('admin::edit-user.update-status-and-role-form', UpdateStatusAndRoleForm::class);
        Livewire::component('admin::edit-user.update-password-form', UpdatePasswordForm::class);
        Livewire::component('admin::edit-user.update-password-without-confirm-form-modal', UpdatePasswordWithoutConfirmFormModal::class);
        Livewire::component('admin::edit-user.two-factor-authentication-form', \MicroweberPackages\User\Http\Livewire\Admin\TwoFactorAuthenticationForm::class);
        Livewire::component('admin::edit-user.logout-other-browser-sessions-form', \MicroweberPackages\User\Http\Livewire\Admin\LogoutOtherBrowserSessionsForm::class);
        Livewire::component('admin::edit-user.delete-user-form', DeleteUserForm::class);
        Livewire::component('admin::edit-user.login-as-user-form', LoginAsUserForm::class);

        Livewire::component('admin::user-tos-log', UserTosLogModal::class);
        Livewire::component('admin::user-login-attempts', UserLoginAttemptsModal::class);

        Livewire::component('user::profile.two-factor-authentication-form', TwoFactorAuthenticationForm::class);
        Livewire::component('user::profile.logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);


        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);
    }

    public function registerMenu()
    {
        AdminManager::getMenuInstance('left_menu_top')->addChild('Users', [
            'uri' => route('admin.users.index'),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M400 576q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80 896V784q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404 696h-4q-71 0-127.5 18T180 750q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584 852l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628 596l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732 876l-12 60h-80Zm40-120q33 0 56.5-23.5T760 736q0-33-23.5-56.5T680 656q-33 0-56.5 23.5T600 736q0 33 23.5 56.5T680 816ZM400 496q33 0 56.5-23.5T480 416q0-33-23.5-56.5T400 336q-33 0-56.5 23.5T320 416q0 33 23.5 56.5T400 496Zm0-80Zm12 400Z"/></svg>'
            ]
        ]);

        AdminManager::getMenuInstance('left_menu_top')
            ->menuItems
            ->getChild('Users')
            ->setExtra('orderNumber', 6);
    }

    public function boot()
    {

        /**
         * @property \MicroweberPackages\User\UserManager $user_manager
         */

        [$publicKey, $privateKey] = [
            storage_path('oauth-public.key'),
            storage_path('oauth-private.key'),
        ];

        $need_to_generate_keys = false;
        if (!is_file($publicKey) or !is_file($privateKey)) {
            $need_to_generate_keys = true;
        }
        if ($need_to_generate_keys) {
            $keys = RSAKeys::createKey( 4096);
            file_put_contents($publicKey, \Arr::get($keys, 'publickey'));
            file_put_contents($privateKey, \Arr::get($keys, 'privatekey'));
        }

        $this->app->register(\Laravel\Passport\PassportServiceProvider::class);
        $this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);

     //   Passport::ignoreMigrations();

        $this->app->singleton('user_manager', function ($app) {
            return new UserManager();
        });

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
}
