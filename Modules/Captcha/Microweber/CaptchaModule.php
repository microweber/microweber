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


    }
}
