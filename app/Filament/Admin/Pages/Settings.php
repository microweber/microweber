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
        $settingsPages[] = new SettingsGeneral();
        $settingsPages[] = new SettingsEmail();
        $settingsPages[] = new SettingsTemplate();
        $settingsPages[] = new SettingsSeo();
        $settingsPages[] = new SettingsAdvanced();
        $settingsPages[] = new SettingsFiles();
        $settingsPages[] = new SettingsLoginRegister();
        $settingsPages[] = new SettingsLanguage();
        $settingsPages[] = new SettingsPrivacyPolicy();
        $settingsPages[] = new SettingsUpdates();

        $settingsPages[] = new SettingsShopGeneral();
        $settingsPages[] = new SettingsShopShipping();
        $settingsPages[] = new SettingsShopCoupons();
        $settingsPages[] = new SettingsShopOffers();
        $settingsPages[] = new SettingsShopPayments();
        $settingsPages[] = new SettingsShopTaxes();
        $settingsPages[] = new SettingsShopInvoices();
        $settingsPages[] = new SettingsShopAutoRespondEmail();
        $settingsPages[] = new SettingsShopOther();


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
