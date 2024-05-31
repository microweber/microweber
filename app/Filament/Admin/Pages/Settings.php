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
        $settingsPages[] = new SettingsUpdates();
        $settingsPages[] = new SettingsEmail();
        $settingsPages[] = new SettingsTemplate();
        $settingsPages[] = new SettingsSeo();
        $settingsPages[] = new SettingsAdvanced();
        $settingsPages[] = new SettingsFiles();
        $settingsPages[] = new SettingsLoginRegister();
        $settingsPages[] = new SettingsLanguage();
        $settingsPages[] = new SettingsPrivacyPolicy();


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
                'router' => (new $settingsPage)::getNavigationUrl(),
            ];
        }

        return [
            'settingsGroups' => $settingsGroups,
        ];
    }
}
