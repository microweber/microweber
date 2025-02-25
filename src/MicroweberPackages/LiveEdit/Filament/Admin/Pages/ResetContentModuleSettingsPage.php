<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ResetContentModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/reset_content';

    protected static string $view = 'filament-panels::components.layout.simple-form';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                View::make('microweber-live-edit::reset-content')

            ]);
    }
}
