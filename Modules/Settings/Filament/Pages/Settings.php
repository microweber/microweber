<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Filament\Support\FilamentHelpers;


class Settings extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'modules.settings::filament.admin.pages.settings-main';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 97;


    public function getTitle(): string
    {
        return '';
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
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
        $settingsPages[] = new AdminLoginRegisterPage();
        $settingsPages[] = new AdminLanguagePage();
        $settingsPages[] = new AdminPrivacyPolicyPage();
        $settingsPages[] = new AdminMaintenanceModePage();
        $settingsPages[] = new AdminCustomTagsPage();

        $settingsPages[] = new AdminShopGeneralPage();
         $settingsPages[] = new AdminShopAutoRespondEmailPage();
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
        $panelNavigationItems = Filament::getCurrentPanel()->getNavigation();

        if ($panelNavigationItems) {
            foreach ($panelNavigationItems as $navGroup) {

                if (method_exists($navGroup, 'getLabel') && !str_ends_with($navGroup->getLabel(), 'Settings')) {
                    continue;
                }
                if (method_exists($navGroup, 'getLabel') && $navGroup->getLabel() === 'Settings') {
                    continue;
                }

                $settingsGroupsNavGroup = $this->buildNavFromPanelNavGroup($navGroup);

                if (!empty($settingsGroupsNavGroup)) {
                    foreach ($settingsGroupsNavGroup as $itemsNavGroup) {
                        foreach ($itemsNavGroup as $itemsNavGroupItem) {
                            $settingsGroups[$navGroup->getLabel()][] = $itemsNavGroupItem;
                        }
                    }
                }

            }
        }
        $positionFallback = 0;
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

            if (!isset($description) or $description == '') {

                $reflectionClass = new \ReflectionClass($instance);
                 $descriptionProp = $reflectionClass->hasProperty('description') ? $reflectionClass->getProperty('description') : null;
                if ($descriptionProp) {
                     $description = $descriptionProp->getValue($instance);
                }
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

            $position = $positionFallback;
            if (method_exists($instance, 'getNavigationSort')) {

                $position = $settingsPage::getNavigationSort() ?? $positionFallback;
            }


            $settingsGroups[$group][] = [
                'title' => $title,
                'description' => $description,
                'heading' => $heading,
                'slug' => $slug,
                'icon' => $icon,
                'url' => $url,
                'position' => $position,
            ];


            $positionFallback++;

        }

        // Add shortcuts for modules that run as separate Filament panels
        if (class_exists(\Modules\Newsletter\Providers\NewsletterFilamentAdminPanelProvider::class)) {
            $settingsGroups['Email Settings'][] = [
                'title' => 'Newsletter',
                'description' => 'Manage email campaigns, subscribers and lists',
                'heading' => 'Newsletter',
                'slug' => 'newsletter',
                'icon' => 'heroicon-o-megaphone',
                'url' => admin_url('newsletter'),
                'position' => 50,
            ];
        }

        if (class_exists(\Modules\Billing\Providers\BillingFilamentAdminPanelProvider::class)) {
            $settingsGroups['Shop Settings'][] = [
                'title' => 'Billing',
                'description' => 'Manage subscriptions, plans and billing',
                'heading' => 'Billing',
                'slug' => 'billing',
                'icon' => 'heroicon-o-currency-dollar',
                'url' => admin_url('billing'),
                'position' => 50,
            ];
        }

        $topOrder = [
            'Website Settings',
            'Shop Settings',
            'Customization Settings',
            'Email Settings',
            'System Settings',
            'Language Settings',
        ];
        $settingsGroups = array_merge(array_flip($topOrder), $settingsGroups);

        foreach ($settingsGroups as $group => $items) {
            if (!is_array($items)) {
                unset($settingsGroups[$group]);
                continue;
            }
            usort($items, function ($a, $b) {
                if (isset($a['position']) && isset($b['position']) && $a['position'] === $b['position']) {
                    return 0;
                } else if (!isset($a['position']) && !isset($b['position'])) {
                    return 0;
                } elseif (!isset($a['position'])) {
                    return 1;
                } elseif (!isset($b['position'])) {
                    return -1;
                }

                return $a['position'] <=> $b['position'];
            });
            $settingsGroups[$group] = $items;
        }

        return [
            'settingsGroups' => $settingsGroups,
        ];
    }

    /**
     * Extract item data from a navigation item object.
     *
     * Handles both parent and child navigation items with consistent
     * error handling and fallback logic.
     */
    private function extractItemData(object $item, string $defaultIcon = ''): array
    {
        $itemData = [
            'title' => '',
            'description' => '',
            'heading' => '',
            'slug' => '',
            'icon' => $defaultIcon,
            'url' => '',
        ];

        try {
            if (method_exists($item, 'getLabel')) {
                $itemData['title'] = $item->getLabel();
                $itemData['heading'] = $item->getLabel();
            }
        } catch (\Exception $e) {
            Log::debug('Settings nav: failed to get label', ['error' => $e->getMessage()]);
        }

        try {
            if (method_exists($item, 'getDescription')) {
                $itemData['description'] = $item->getDescription();
            }
        } catch (\Exception $e) {
            Log::debug('Settings nav: failed to get description', ['error' => $e->getMessage()]);
        }

        if (empty($itemData['description'])) {
            try {
                if (method_exists($item, 'getNavigationLabel')) {
                    $itemData['description'] = $item->getNavigationLabel();
                }
            } catch (\Exception $e) {
                Log::debug('Settings nav: failed to get navigation label', ['error' => $e->getMessage()]);
            }
        }

        try {
            if (method_exists($item, 'getSlug')) {
                $itemData['slug'] = $item->getSlug();
            }
        } catch (\Exception $e) {
            Log::debug('Settings nav: failed to get slug', ['error' => $e->getMessage()]);
        }

        if (empty($itemData['icon'])) {
            $itemData['icon'] = FilamentHelpers::getNavigationItemIcon($item);
        }

        try {
            if (method_exists($item, 'getIcon') && !empty($item->getIcon())) {
                $itemData['icon'] = $item->getIcon();
            }
        } catch (\Exception $e) {
            Log::debug('Settings nav: failed to get icon', ['error' => $e->getMessage()]);
        }

        try {
            if (method_exists($item, 'getUrl')) {
                $itemData['url'] = $item->getUrl();
            }
        } catch (\Exception $e) {
            Log::debug('Settings nav: failed to get URL', ['error' => $e->getMessage()]);
        }

        if (empty($itemData['description'])) {
            $itemData['description'] = FilamentHelpers::getNavigationItemDescription($item);
        }

        return $itemData;
    }

    private function buildNavFromPanelNavGroup(NavigationGroup $navGroup): array
    {
        $settingsGroups = [];

        $groupLabel = '';
        if (method_exists($navGroup, 'getLabel')) {
            $groupLabel = $navGroup->getLabel();
        } elseif (method_exists($navGroup, 'getTitle')) {
            $groupLabel = $navGroup->getTitle();
        }

        if (!method_exists($navGroup, 'getItems')) {
            return $settingsGroups;
        }

        foreach ($navGroup->getItems() as $item) {
            $settingsGroups[$groupLabel][] = $this->extractItemData($item);

            if (method_exists($item, 'getChildItems')) {
                try {
                    $childItems = $item->getChildItems();
                    if (!empty($childItems)) {
                        foreach ($childItems as $childItem) {
                            $settingsGroups[$groupLabel][] = $this->extractItemData($childItem, 'mw-general');
                        }
                    }
                } catch (\Exception $e) {
                    Log::debug('Settings nav: failed to get child items', ['error' => $e->getMessage()]);
                }
            }
        }

        return $settingsGroups;
    }
}
