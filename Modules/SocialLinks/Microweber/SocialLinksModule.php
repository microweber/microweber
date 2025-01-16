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
    public static string $templatesNamespace = 'modules.social_links::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $viewData['facebook_enabled'] = $this->getOption('facebook_enabled') == '1';
        $viewData['twitter_enabled'] = $this->getOption('twitter_enabled') == '1';
        $viewData['pinterest_enabled'] = $this->getOption('pinterest_enabled') == '1';
        $viewData['linkedin_enabled'] = $this->getOption('linkedin_enabled') == '1';
        $viewData['viber_enabled'] = $this->getOption('viber_enabled') == '1';
        $viewData['whatsapp_enabled'] = $this->getOption('whatsapp_enabled') == '1';
        $viewData['telegram_enabled'] = $this->getOption('telegram_enabled') == '1';
        $viewData['youtube_enabled'] = $this->getOption('youtube_enabled') == '1';
        $viewData['instagram_enabled'] = $this->getOption('instagram_enabled') == '1';
        $viewData['github_enabled'] = $this->getOption('github_enabled') == '1';
        $viewData['soundcloud_enabled'] = $this->getOption('soundcloud_enabled') == '1';
        $viewData['mixcloud_enabled'] = $this->getOption('mixcloud_enabled') == '1';
        $viewData['discord_enabled'] = $this->getOption('discord_enabled') == '1';
        $viewData['skype_enabled'] = $this->getOption('skype_enabled') == '1';


        $viewData['facebook_url'] = $this->getOption('facebook_url');
        $viewData['twitter_url'] = $this->getOption('twitter_url');
        $viewData['pinterest_url'] = $this->getOption('pinterest_url');
        $viewData['linkedin_url'] = $this->getOption('linkedin_url');
        $viewData['viber_url'] = $this->getOption('viber_url');
        $viewData['whatsapp_url'] = $this->getOption('whatsapp_url');
        $viewData['telegram_url'] = $this->getOption('telegram_url');
        $viewData['youtube_url'] = $this->getOption('youtube_url');
        $viewData['instagram_url'] = $this->getOption('instagram_url');
        $viewData['github_url'] = $this->getOption('github_url');
        $viewData['soundcloud_url'] = $this->getOption('soundcloud_url');
        $viewData['mixcloud_url'] = $this->getOption('mixcloud_url');
        $viewData['discord_url'] = $this->getOption('discord_url');
        $viewData['skype_url'] = $this->getOption('skype_url');

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
