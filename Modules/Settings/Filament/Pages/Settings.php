<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use MicroweberPackages\Filament\Facades\FilamentRegistry;


class Settings extends Page
{
    protected static ?string $navigationIcon = 'mw-settings';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-main';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 97;

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return '';
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
        $settingsPages[] = new AdminWebManifestPage();
        $settingsPages[] = new AdminExperimentalPage();
        $settingsPages[] = new AdminMaintenanceModePage();
        $settingsPages[] = new AdminUiColorsPage();
        $settingsPages[] = new AdminPoweredByPage();
        $settingsPages[] = new AdminRobotsPage();
        $settingsPages[] = new AdminTrustProxiesPage();
        $settingsPages[] = new AdminCustomTagsPage();

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
        $registeredSettingsResources = FilamentRegistry::getResources(self::class, Filament::getCurrentPanel()->getId());


        if (!empty($registeredSettingsPages)) {
            foreach ($registeredSettingsPages as $registeredSettingsPage) {
                $settingsPages[] = new $registeredSettingsPage;
            }
        }

        if (!empty($registeredSettingsResources)) {
            foreach ($registeredSettingsResources as $registeredSettingsResource) {

                $settingsPages[] = new $registeredSettingsResource;
            }
        }

        $settingsGroups = [];
        $panel = Filament::getCurrentPanel()->getId();
        $panelNavigationGroups = Filament::getCurrentPanel()->getNavigationGroups();
        if ($panelNavigationGroups) {
            foreach ($panelNavigationGroups as $navGroup) {
                $groupLabel = $navGroup->getLabel() ?? 'Other Settings';

                foreach ($navGroup->getItems() as $item) {
                    $settingsGroups[$groupLabel][] = [
                        'title' => $item->getLabel(),
                        'description' => $item->getDescription() ?? '',
                        'heading' => $item->getLabel(),
                        'slug' => $item->getSlug() ?? '',
                        'icon' => $item->getIcon() ?? 'heroicon-o-chevron-right',
                        'url' => $item->getUrl(),
                    ];

                    // Add child items if they exist
                    if (method_exists($item, 'getChildItems') && !empty($item->getChildItems())) {
                        foreach ($item->getChildItems() as $childItem) {
                            $settingsGroups[$groupLabel][] = [
                                'title' => $childItem->getLabel(),
                                'description' => $childItem->getDescription() ?? '',
                                'heading' => $childItem->getLabel(),
                                'slug' => $childItem->getSlug() ?? '',
                                'icon' => $childItem->getIcon() ?? 'heroicon-o-chevron-right',
                                'url' => $childItem->getUrl(),
                            ];
                        }
                    }
                }
            }


        }


        foreach ($settingsPages as $settingsPage) {
            $instance = new $settingsPage;
            $defaultGroup = 'Website Settings';

            $group = method_exists($settingsPage, 'getNavigationGroup')
                ? $settingsPage::getNavigationGroup()
                : $defaultGroup;

            $slug = method_exists($instance, 'getSlug') ? $instance->getSlug() : '';

            if (isset($settingsGroups[$group]) && array_search($slug, array_column($settingsGroups[$group], 'slug')) !== false) {
                continue;
            }

            $title = '';
            if (method_exists($instance, 'getTitle')) {
                $title = $instance->getTitle();
            } elseif (method_exists($instance, 'getNavigationLabel')) {
                $title = $instance->getNavigationLabel();
            }

            $description = '';
            if (method_exists($instance, 'getDescription')) {
                $description = $instance->getDescription();
            }

            $heading = '';
            if (method_exists($instance, 'getHeading')) {
                $heading = $instance->getHeading();
            }

            $icon = '';
            if (method_exists($instance, 'getNavigationIcon')) {
                $icon = $instance->getNavigationIcon();
            }

            $url = '';
            if (method_exists($settingsPage, 'getNavigationUrl')) {
                $url = $settingsPage::getNavigationUrl();
            }

            $settingsGroups[$group][] = [
                'title' => $title,
                'description' => $description,
                'heading' => $heading,
                'slug' => $slug,
                'icon' => $icon,
                'url' => $url,
            ];
        }


        return [
            'settingsGroups' => $settingsGroups,
        ];
    }
}
