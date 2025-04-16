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
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Middleware\SubstituteBindings;
use MicroweberPackages\Filament\Plugins\MicroweberFilamentSocialitePlugin;
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
//            ->renderHook(
//                'panels::auth.login.form.after',
//                fn() => view('modules.profile::auth.social-login')
//            )
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
                MicroweberFilamentSocialitePlugin::make()->configure()
            );

        //    ->viteTheme('resources/css/filament/profile/theme.css');
    }
}
