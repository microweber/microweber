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
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Middleware\SubstituteBindings;
use MicroweberPackages\Filament\Plugins\MicroweberFilamentSocialitePlugin;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
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
            ->colors(\MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme::getColors())
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
            ->pages(array_merge([
                Pages\Dashboard::class,
                EditProfile::class,
                TwoFactorAuth::class,
            ], \MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry::getPages('profile', 'profile')))
            // Plain strings — NOT __(): navigationGroups is evaluated while the
            // panel is registered during boot, before the DB-backed translation
            // loader is ready, so __() here triggers "connection() on null".
            // The pages' getNavigationGroup() __() calls resolve to these same
            // identity strings at render time, so ordering still matches.
            ->navigationGroups([
                'Profile',
                'Orders & Billing',
                'Security',
                'Vendor',
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
            ])
            ->plugin(new MicroweberFilamentTheme())
            ->plugin(
                MicroweberFilamentSocialitePlugin::make()->configure()
            )
            ->plugin(\MicroweberPackages\Fortify\Filament\MicroweberFortifyPlugin::make());

        //    ->viteTheme('resources/css/filament/profile/theme.css');
    }
}
