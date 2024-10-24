<?php

namespace Modules\Captcha\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Captcha\Filament\CaptchaModuleSettings;

class CaptchaModule extends BaseModule
{
    public static string $name = 'Captcha module';
    public static string $icon = 'heroicon-o-shield-check';
    public static string $categories = 'security, captcha';
    public static int $position = 3;
    public static string $settingsComponent = CaptchaModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();
        $viewData['form_id'] = uniqid('cap');
        $viewData['captcha_provider'] = $this->getCaptchaProvider();
        $viewData['template'] = $this->getTemplate();
        $viewData['recaptcha_v3_secret_key'] = $this->getRecaptchaSecretKey('v3');
        $viewData['recaptcha_v2_secret_key'] = $this->getRecaptchaSecretKey('v2');
        $viewData['moduleTemplate'] = $this->getModuleTemplate();

        return view('modules.captcha::templates.default', $viewData);
    }

    private function getCaptchaProvider()
    {
        return get_option('provider', 'captcha');
    }

    private function getTemplate()
    {
        $template = get_option('data-template', $this->params['id']);
        if (empty($template) && isset($this->params['template'])) {
            $template = $this->params['template'];
        }
        return $template;
    }

    private function getRecaptchaSecretKey($version)
    {
        return get_option("recaptcha_{$version}_secret_key", 'captcha');
    }

    private function getModuleTemplate()
    {
        $moduleTemplate = get_option('template', $this->params['id']);
        if (empty($moduleTemplate)) {
            $moduleTemplate = get_option('data-template', $this->params['id']);
        }
        if (empty($moduleTemplate) && isset($this->params['template'])) {
            $moduleTemplate = $this->params['template'];
        }
        return $moduleTemplate;
    }
}
