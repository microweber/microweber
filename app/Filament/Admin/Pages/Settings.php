<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'mw-settings';

    protected static string $view = 'filament.admin.pages.settings';


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

        $settingsPages[] = new AdminShopGeneral();
        $settingsPages[] = new AdminShopShipping();
        $settingsPages[] = new AdminShopCouponsPage();
        $settingsPages[] = new AdminShopOffersPage();
        $settingsPages[] = new AdminShopPaymentsPage();
        $settingsPages[] = new AdminShopTaxesPage();
        $settingsPages[] = new AdminShopInvoicesPage();
        $settingsPages[] = new AdminShopAutoRespondEmailPage();
        $settingsPages[] = new AdminShopOtherPage();


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
