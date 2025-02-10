<?php

namespace Modules\LayoutContent\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class LayoutContentModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'layout_content';
    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }
}
