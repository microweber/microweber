<?php

namespace Modules\SocialLinks\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\SocialLinks\Filament\SocialLinksModuleSettings;
use Illuminate\View\View;

class SocialLinksModule extends BaseModule
{
    // Module configuration
    public static string $name = 'Social Links';
    public static string $module = 'social_links';
    public static string $icon = 'modules.social-links-icon';
    public static string $categories = 'social';
    public static int $position = 9;
    public static string $settingsComponent = SocialLinksModuleSettings::class;
    public static string $templatesNamespace = 'modules.social_links::templates';

    // Social networks configuration
    private const SOCIAL_NETWORKS = [
        'facebook',
        'x',
        'pinterest',
        'linkedin',
        'viber',
        'whatsapp',
        'telegram',
        'youtube',
        'instagram',
        'github',
        'soundcloud',
        'discord',
        'skype'
    ];

    public function render(): View
    {
        $viewData = $this->getViewData();
        $viewData = array_merge($viewData, $this->getSocialNetworksData());

        $template = $this->getBladeTemplate($viewData['template'] ?? 'default');

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    private function getSocialNetworksData(): array
    {
        $data = [];
        foreach (self::SOCIAL_NETWORKS as $network) {
            $data["{$network}_enabled"] = $this->getOption("{$network}_enabled") == '1';
            $data["{$network}_url"] = $this->getOption("{$network}_url");
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
            $defaultNetworks = ['facebook', 'instagram', 'linkedin'];
            foreach ($defaultNetworks as $network) {
                $data["{$network}_enabled"] = true;
                $data["{$network}_url"] = '';
                save_option("{$network}_enabled", '1', $this->params['id']);
                save_option("{$network}_url", '', $this->params['id']);
            }
        }

        return $data;
    }

    public function getBladeTemplate(string $template): string
    {
        return view()->exists(static::$templatesNamespace . '.' . $template)
            ? $template
            : 'default';
    }
}
