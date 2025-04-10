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
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use Modules\Billing\Filament\Frontend\Pages\UserSubscriptionPanel;
use Modules\Profile\Filament\Pages\EditProfile;
use Modules\Profile\Filament\Pages\ForgotPassword;
use Modules\Profile\Filament\Pages\Login;
use Modules\Profile\Filament\Pages\Register;
use Modules\Profile\Filament\Pages\TwoFactorAuth;

class BillingFilamentFrontendPanelProvider extends PanelProvider
{

    public string $filamentId = 'billing';
    public string $filamentPath = 'billing';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id($this->filamentId)
            ->path($this->filamentPath)
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(ForgotPassword::class)
            ->colors([
                'primary' => Color::Purple,
            ])
            ->discoverResources(
                in: __DIR__ . '/../Filament/Frontend/Resources',
                for: 'Modules\\Billing\\Filament\\Frontend\\Resources')
            ->discoverPages(
                in: __DIR__ . '/../Filament/Frontend/Pages',
                for: 'Modules\\Billing\\Filament\\Frontend\\Pages')
            ->pages([
                UserSubscriptionPanel::class,
            ])
            ->discoverWidgets(in: __DIR__ . '/../Filament/Frontend/Widgets',
                for: 'Modules\\Billing\\Filament\\Frontend\\Widgets')
            ->widgets([
                //     Widgets\AccountWidget::class,
            ])
            ->middleware([

                'web',

                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
              ->plugin(new MicroweberFilamentTheme())

            ->authMiddleware([
                Authenticate::class,
            ]);
        //    ->viteTheme('resources/css/filament/profile/theme.css');
    }
}
