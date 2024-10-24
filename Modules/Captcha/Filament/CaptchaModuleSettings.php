<?php

namespace Modules\Captcha\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CaptchaModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'captcha';

    public function form(Form $form): Form
    {
        return $form
            ->schema([


            ]);
    }
}
