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


        $form_id = uniqid('cap');
        $captcha_provider = get_option('provider', 'captcha');

        $template = get_option('data-template', $params['id']);

        if (($template == false or ($template == '')) and isset($params['template'])) {
            $template = $params['template'];
        }

        $recaptcha_v3_secret_key = get_option('recaptcha_v3_secret_key', 'captcha');
        $recaptcha_v2_secret_key = get_option('recaptcha_v2_secret_key', 'captcha');

        $viewData['form_id'] = $form_id;
        $viewData['captcha_provider'] = $captcha_provider;
        $viewData['template'] = $template;
        $viewData['recaptcha_v3_secret_key'] = $recaptcha_v3_secret_key;
        $viewData['recaptcha_v2_secret_key'] = $recaptcha_v2_secret_key;


        $moduleTemplate = get_option('template', $this->params['id']);
        if (empty($moduleTemplate)) {
            $moduleTemplate = get_option('data-template', $this->params['id']);
        }

        if ($moduleTemplate == false and isset($this->params['template'])) {
            $moduleTemplate = $this->params['template'];
        }

        $viewData['moduleTemplate'] = $moduleTemplate;

        return view('modules.captcha::templates.default', $viewData);
    }
}
