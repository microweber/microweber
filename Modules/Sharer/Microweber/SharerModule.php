<?php

namespace Modules\Sharer\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Sharer\Filament\SharerModuleSettings;

class SharerModule extends BaseModule
{
    public static string $name = 'Sharer';
    public static string $module = 'sharer';
    public static string $icon = 'modules.sharer-icon';
    public static string $categories = 'social';
    public static int $position = 210;
    public static string $settingsComponent =  SharerModuleSettings::class;

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

        return view('modules.sharer::templates.default', $viewData);
    }
}
