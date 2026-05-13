<?php

namespace Modules\Checkout\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use MicroweberPackages\Admin\Filament\MwColors;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use Modules\Checkout\Filament\Resources\CheckoutResource;
use Modules\Checkout\Filament\Resources\Pages\CheckoutPage;

class FilamentCheckoutPanelProvider extends PanelProvider
{
    public string $filamentId = 'checkout';
    public string $filamentPath = 'checkout';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id($this->filamentId)
            ->path($this->filamentPath)
            ->navigation(false)
            ->brandLogo(function () {
                return mw()->ui->admin_logo();
            })
            // AI-209 (task-2026-05-13-e8ebcf) — checkout primary blue now
            // shares the MwColors::Blue palette anchored at Bootstrap
            // #0d6efd, the same blue the admin panel + public storefront
            // CTAs use. Was Color::Blue (Tailwind blue-500 #3b82f6), which
            // made the checkout "Next" button render in a visibly different
            // hue than the /shop "All Categories" Bootstrap-blue button on
            // the same device.
            ->colors([
                'primary' => MwColors::Blue,
            ])
            //->discoverResources(in: __DIR__ . '/../Filament/Resources', for: 'Modules\\Checkout\\Filament\\Resources')
            // ->discoverPages(in: __DIR__ . '/../Filament/Pages', for: 'Modules\\Checkout\\Filament\\Pages')
            ->pages([
                CheckoutPage::class,
            ])
            ->resources([
                CheckoutResource::class,
            ])
            ->plugin(new MicroweberFilamentTheme())
            ->middleware([

               'web',

                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,

            ]);



    }
}
