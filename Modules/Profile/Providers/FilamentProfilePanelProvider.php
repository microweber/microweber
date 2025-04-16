<?php

namespace Modules\Profile\Providers;

use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MicroweberPackages\Filament\Http\Middleware\AuthenticateUser;
use MicroweberPackages\User\Models\SocialiteUser;
use Modules\Profile\Filament\Pages\EditProfile;
use Modules\Profile\Filament\Pages\ForgotPassword;
use Modules\Profile\Filament\Pages\Login;
use Modules\Profile\Filament\Pages\Register;
use Modules\Profile\Filament\Pages\TwoFactorAuth;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

class FilamentProfilePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('profile')
            ->path('profile')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(ForgotPassword::class)
            ->colors([
                'primary' => Color::Purple,
            ])
            ->renderHook(
                'panels::auth.login.form.after',
                fn() => view('modules.profile::auth.social-login')
            )
            ->discoverResources(
                in: __DIR__ . '/../Filament/Resources',
                for: 'Modules\\Profile\\Filament\\Resources')
            ->discoverPages(
                in: __DIR__ . '/../Filament/Pages',
                for: 'Modules\\Profile\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                EditProfile::class,
                TwoFactorAuth::class,
            ])
            ->discoverWidgets(in: __DIR__ . '/../Filament/Widgets',
                for: 'Modules\\Profile\\Filament\\Widgets')
            ->widgets([
                //     Widgets\AccountWidget::class,
            ])
            ->middleware([

                'web',

                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->plugin(
                FilamentSocialitePlugin::make()
                    // (required) Add providers corresponding with providers in `config/services.php`.
                    ->providers([
                        Provider::make('google')
                            ->label('Google')

                    ])
                    // (optional) Override the panel slug to be used in the oauth routes. Defaults to the panel ID.
                   // ->slug('admin')
                    // (optional) Enable/disable registration of new (socialite-) users.
                    ->registration(true)
                    // (optional) Enable/disable registration of new (socialite-) users using a callback.
                    // In this example, a login flow can only continue if there exists a user (Authenticatable) already.
                    ->registration(fn (string $provider, SocialiteUserContract $oauthUser, ?Authenticatable $user) => (bool) $user)
                    // (optional) Change the associated model class.
                    ->userModelClass(\App\Models\User::class)
                    // (optional) Change the associated socialite class (see below).
                    ->socialiteUserModelClass(\App\Models\User::class)
            );

        //    ->viteTheme('resources/css/filament/profile/theme.css');
    }
}
