<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools;

use Filament\Forms\Components\View;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ModulePresetsModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/module_presets';

    protected static string $view = 'filament-panels::components.layout.simple-form';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                View::make('microweber-live-edit::editor-tools.render-module-presets')

            ]);
    }
}
