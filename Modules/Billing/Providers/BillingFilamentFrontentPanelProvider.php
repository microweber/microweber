<?php

namespace Modules\Billing\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MicroweberPackages\Filament\Http\Middleware\AuthenticateUser;
use Modules\Billing\Filament\Frontend\Pages\UserSubscriptionPanel;
use Modules\Profile\Filament\Pages\EditProfile;
use Modules\Profile\Filament\Pages\ForgotPassword;
use Modules\Profile\Filament\Pages\Login;
use Modules\Profile\Filament\Pages\Register;
use Modules\Profile\Filament\Pages\TwoFactorAuth;

class BillingFilamentFrontentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('billing')
            ->path('billing')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(ForgotPassword::class)
            ->colors([
                'primary' => Color::Purple,
            ])
            ->discoverResources(
                in: __DIR__ . '/../Filament/Resources/Frontend',
                for: 'Modules\\Billing\\Filament\\Resources\\Frontend')
            ->discoverPages(
                in: __DIR__ . '/../Filament/Pages/Frontend',
                for: 'Modules\\Billing\\Filament\\Pages\\Frontend')
            ->pages([
                 UserSubscriptionPanel::class,
             ])
            ->discoverWidgets(in: __DIR__ . '/../Filament/Widgets/Frontend',
                for: 'Modules\\Billing\\Filament\\Widgets\\Frontend')
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
            ]);
        //    ->viteTheme('resources/css/filament/profile/theme.css');
    }
}
