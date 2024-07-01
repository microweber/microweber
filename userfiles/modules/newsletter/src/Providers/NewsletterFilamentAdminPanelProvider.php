<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\Filament\MicroweberTheme;
use MicroweberPackages\Marketplace\Filament\MarketplaceFilamentPlugin;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\CreateCampaign;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\EditCampaign;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\Homepage;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\ListResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;

class NewsletterFilamentAdminPanelProvider extends FilamentAdminPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            // ->viteTheme('resources/css/microweber-admin-filament.scss', 'public/build')
            ->viteTheme('resources/css/filament/admin/theme.css', 'public/build')
            ->id('admin-newsletter')
            ->path('admin/newsletter')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->font('Inter')
            ->brandLogoHeight('34px')
            ->brandLogo(fn () => view('microweber-module-newsletter::livewire.filament.admin.logo'))
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->pages([
                Homepage::class,
                CreateCampaign::class,
                EditCampaign::class
            ])
            ->resources([
                SenderAccountsResource::class,
                SubscribersResource::class,
                TemplatesResource::class,
                CampaignResource::class,
                ListResource::class
            ])
            ->widgets([
                // Widgets\AccountWidget::class,
                //  Widgets\FilamentInfoWidget::class,
//                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware($this->getPanelMiddlewares())
            ->authGuard('web')
            ->authMiddleware([
                //  Authenticate::class,
                \MicroweberPackages\Filament\Http\Middleware\Authenticate::class,
                //  Admin::class,
            ]);

        $panel->renderHook(
            name: PanelsRenderHook::SIDEBAR_NAV_START,
            hook: fn(): string => Blade::render('
            <div class="p-4 w-full mb-4">
            <x-filament::button outlined
                    href="'.admin_url('newsletter/create-campaign').'"
                    tag="a"
                    icon="heroicon-o-pencil"
                    class="w-full"
                >
                    Create
                </x-filament::button>
                </div>
                ')
        );


        return $panel;
    }
}
