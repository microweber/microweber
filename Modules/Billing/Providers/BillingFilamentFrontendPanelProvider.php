<?php

namespace Modules\Billing\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Routing\Middleware\SubstituteBindings;
use MicroweberPackages\Filament\Plugins\MicroweberFilamentSocialitePlugin;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use Modules\Billing\Filament\Pages\ActiveSubscriptions;
use Modules\Billing\Filament\Pages\PurchaseCancelPage;
use Modules\Billing\Filament\Pages\PurchaseSuccessPage;
use Modules\Billing\Filament\Pages\SubscriptionCancelPage;
use Modules\Billing\Filament\Pages\SubscriptionSuccessPage;
use Modules\Billing\Filament\Pages\UserSubscriptionPanel;
use Modules\Profile\Filament\Pages\ForgotPassword;
use Modules\Profile\Filament\Pages\Login;
use Modules\Profile\Filament\Pages\Register;

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
                ActiveSubscriptions::class,
                SubscriptionSuccessPage::class,
                SubscriptionCancelPage::class,
                PurchaseSuccessPage::class,
                PurchaseCancelPage::class,
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
              ->plugin(MicroweberFilamentSocialitePlugin::make()->admin()->configure())

            ->authMiddleware([
                Authenticate::class,
            ]);
        //    ->viteTheme('resources/css/filament/profile/theme.css');
    }
}
