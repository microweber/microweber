<?php

namespace Modules\Sharer\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Sharer\Filament\SharerModuleSettings;
use Illuminate\View\View;

class SharerModule extends BaseModule
{
    // Module configuration
    public static string $name = 'Sharer';
    public static string $module = 'sharer';
    public static string $icon = 'modules.sharer-icon';
    public static string $categories = 'social';
    public static int $position = 210;
    public static string $settingsComponent = SharerModuleSettings::class;
    public static string $templatesNamespace = 'modules.sharer::templates';

    // Social networks configuration
    private const SOCIAL_NETWORKS = [
        'facebook',
        'x',
        'pinterest',
        'linkedin',
        'viber',
        'whatsapp',
        'telegram'
    ];

    public function render(): View
    {
        $viewData = $this->getViewData();
        $viewData = array_merge($viewData, $this->getSocialNetworksData());

        return view(static::$templatesNamespace . '.default', $viewData);
    }

    public function getSocialNetworksData(): array
    {
        $data = [];
        foreach (self::SOCIAL_NETWORKS as $network) {
            $data["{$network}_enabled"] = $this->getOption("{$network}_enabled") == '1';
        }

        // Check if any social network is enabled
        $hasEnabledNetworks = false;
        foreach (self::SOCIAL_NETWORKS as $network) {
            if ($data["{$network}_enabled"]) {
                $hasEnabledNetworks = true;
                break;
            }
        }

        // If no networks are enabled, add default ones
        if (!$hasEnabledNetworks) {
            $defaultNetworks = ['facebook', 'x', 'linkedin','whatsapp'];
            foreach ($defaultNetworks as $network) {
                $data["{$network}_enabled"] = true;
                save_option("{$network}_enabled", '1', $this->params['id']);
            }
        }

        return $data;
    }
}
