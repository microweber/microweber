<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use MicroweberPackages\Filament\Facades\FilamentRegistry;


class Settings extends Page
{
    protected static ?string $navigationIcon = 'mw-settings';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-main';

    protected static ?string $navigationGroup = 'Other';

    protected static ?int $navigationSort = 97;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::MaxContent;
    }

    public function getViewData(): array
    {
        $settingsPages = [];
        $settingsPages[] = new AdminGeneralPage();
        $settingsPages[] = new AdminEmailPage();
        $settingsPages[] = new AdminTemplatePage();
        $settingsPages[] = new AdminSeoPage();
        $settingsPages[] = new AdminAdvancedPage();
        $settingsPages[] = new AdminFilesPage();
        $settingsPages[] = new AdminLoginRegisterPage();
        $settingsPages[] = new AdminLanguagePage();
        $settingsPages[] = new AdminPrivacyPolicyPage();
        $settingsPages[] = new AdminUpdatesPage();

        $settingsPages[] = new AdminShopGeneralPage();
        $settingsPages[] = new AdminShopShippingPage();
        $settingsPages[] = new AdminShopCouponsPage();
        $settingsPages[] = new AdminShopOffersPage();
        $settingsPages[] = new AdminShopPaymentsPage();
        $settingsPages[] = new AdminShopTaxesPage();
        $settingsPages[] = new AdminShopInvoicesPage();
        $settingsPages[] = new AdminShopAutoRespondEmailPage();
        $settingsPages[] = new AdminShopOtherPage();
        $settingsPages[] = new AdminShopOtherPage();
        $registeredSettingsPages = FilamentRegistry::getPages(self::class, Filament::getCurrentPanel()->getId());


        if (!empty($registeredSettingsPages)) {
            foreach ($registeredSettingsPages as $registeredSettingsPage) {
                $settingsPages[] = new $registeredSettingsPage;
            }
        }


        $settingsGroups = [];
        foreach ($settingsPages as $settingsPage) {

            $defaultGroup = 'Website Settings';
            $group = (new $settingsPage)::getNavigationGroup();
            if (empty($group)) {
                $group = $defaultGroup;
            }

            $settingsGroups[$group][] = [
                'title' => (new $settingsPage)->getTitle(),
                'description' => (new $settingsPage)->getDescription(),
                'heading' => (new $settingsPage)->getHeading(),
                'slug' => (new $settingsPage)->getSlug(),
                'icon' => (new $settingsPage)->getNavigationIcon(),
                'url' => (new $settingsPage)::getNavigationUrl(),
            ];
        }

        return [
            'settingsGroups' => $settingsGroups,
        ];
    }
}
