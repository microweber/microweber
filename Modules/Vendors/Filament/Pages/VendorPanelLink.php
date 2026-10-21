<?php

namespace Modules\Vendors\Filament\Pages;

use Filament\Pages\Page;
use Modules\Vendors\Services\VendorManager;

/**
 * A single nav entry in the PROFILE panel that sends a vendor to their
 * dedicated Vendor panel (/vendor-panel). The nav item links straight there
 * (getNavigationUrl), and hitting this page's own route just redirects — so
 * vendors can always find their dashboard from the normal profile area.
 */
class VendorPanelLink extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';
    protected string $view = 'modules.vendors::filament.pages.vendor-panel-link';
    protected static ?int $navigationSort = 20;

    public static function getNavigationLabel(): string
    {
        return __('Vendor Dashboard');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Vendor');
    }

    /** The nav item points straight at the vendor panel. */
    public static function getNavigationUrl(): string
    {
        return url('/vendor-panel');
    }

    /** If the page's own route is hit directly, bounce to the panel. */
    public function mount()
    {
        return redirect()->to(url('/vendor-panel'));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && app(VendorManager::class)->isVendor();
    }
}
