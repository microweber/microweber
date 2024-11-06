<?php

namespace Modules\SocialLinks\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\SocialLinks\Filament\SocialLinksModuleSettings;

class SocialLinksModule extends BaseModule
{
    public static string $name = 'Social Links';
    public static string $module = 'social_links';
    public static string $icon = 'modules.social-links-icon';
    public static string $categories = 'social';
    public static int $position = 9;
    public static string $settingsComponent = SocialLinksModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        $viewData['facebook_enabled'] = $this->getOption('facebook_enabled') == '1';
        $viewData['twitter_enabled'] = $this->getOption('twitter_enabled') == '1';
        $viewData['pinterest_enabled'] = $this->getOption('pinterest_enabled') == '1';
        $viewData['linkedin_enabled'] = $this->getOption('linkedin_enabled') == '1';
        $viewData['viber_enabled'] = $this->getOption('viber_enabled') == '1';
        $viewData['whatsapp_enabled'] = $this->getOption('whatsapp_enabled') == '1';

        $viewData['facebook_url'] = $this->getOption('facebook_url');
        $viewData['twitter_url'] = $this->getOption('twitter_url');
        $viewData['pinterest_url'] = $this->getOption('pinterest_url');
        $viewData['linkedin_url'] = $this->getOption('linkedin_url');
        $viewData['viber_url'] = $this->getOption('viber_url');
        $viewData['whatsapp_url'] = $this->getOption('whatsapp_url');

        return view('modules.social_links::templates.default', $viewData);
    }
}
