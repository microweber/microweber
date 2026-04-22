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
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\User\Filament\Pages\ApiApplicationsPage;
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

        FilamentRegistry::registerPage(ApiApplicationsPage::class);

        $this->app->register(\MicroweberPackages\User\Providers\UserSocialiteServiceProvider::class);

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

        [$publicKey, $privateKey] = [
            storage_path('oauth-public.key'),
            storage_path('oauth-private.key'),
        ];

        $need_to_generate_keys = false;

        if (!is_file($publicKey) or !is_file($privateKey)) {
            $need_to_generate_keys = true;
        }

      /*  else if (is_file($privateKey) and filesize($privateKey) < 10){
            $need_to_generate_keys = true;
        }*/

        if ($need_to_generate_keys) {
            $privateKeyGenerate = \MicroweberPackages\User\Services\RSAKeys::createKey(4096);
            $publicKeyGenerate = $privateKeyGenerate->getPublicKey();
            $privateKeyValue = $privateKeyGenerate->toString('PKCS8');
            $publicKeyValue = $publicKeyGenerate->toString('PKCS8');

            file_put_contents($publicKey, $publicKeyValue);
            file_put_contents($privateKey, $privateKeyValue);
            chmod($publicKey, 0600);
            chmod($privateKey, 0600);
        }
        $this->app->register(\Laravel\Passport\PassportServiceProvider::class);
        $this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addYear());

        Passport::tokensCan(self::headlessApiScopes());
        Passport::setDefaultScope(['*']);

        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('scope', \Laravel\Passport\Http\Middleware\CheckToken::class);
        $router->aliasMiddleware('scopes', \Laravel\Passport\Http\Middleware\CheckTokenForAnyScope::class);

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
     * Each module exposes `<slug>:read` and `<slug>:write` so personal
     * tokens can be narrowed to the subset of endpoints they actually
     * need. Tokens issued without explicit scopes default to `['*']`
     * (full access), which preserves pre-scoping token behaviour.
     *
     * @return array<string, string>
     */
    public static function headlessApiScopes(): array
    {
        $modules = [
            'content' => 'Content',
            'pages' => 'Pages',
            'posts' => 'Posts',
            'tags' => 'Tags',
            'comments' => 'Comments',
            'menus' => 'Menus',
            'media' => 'Media files',
            'forms' => 'Contact forms',
            'products' => 'Products',
            'categories' => 'Categories',
            'orders' => 'Orders',
            'coupons' => 'Coupons',
            'shipping' => 'Shipping options',
            'tax' => 'Tax rules',
            'invoices' => 'Invoices',
            'users' => 'Users',
            'customers' => 'Customers',
            'profile' => 'The authenticated user profile',
            'newsletter' => 'Newsletter subscribers',
            'settings' => 'Site settings',
        ];

        $scopes = [];
        foreach ($modules as $slug => $label) {
            $scopes["{$slug}:read"] = "Read {$label}";
            $scopes["{$slug}:write"] = "Create, update and delete {$label}";
        }

        return $scopes;
    }
}
