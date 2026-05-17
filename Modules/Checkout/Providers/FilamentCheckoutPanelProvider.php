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
use Modules\Checkout\Http\Middleware\RedirectEmptyCheckoutToCart;

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

                // task-2026-05-17-7c3881 / AI-851 [P3] — short-circuit the
                // bare /checkout + empty-cart double-redirect chain.
                // Pre-fix: GET /checkout → 302 /checkout/checkout → 302 /cart
                // (two hops, no notice). Post-fix: GET /checkout → 302
                // /cart?notice=empty-cart-no-checkout (single hop + notice
                // banner). Placed BEFORE Filament's panel-default home
                // redirect so it fires on the bare /checkout request and
                // short-circuits the panel chain. Scope-guarded to the
                // BARE /checkout path; subroutes are untouched.
                RedirectEmptyCheckoutToCart::class,

                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,

            ]);



    }
}
