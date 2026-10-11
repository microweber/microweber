<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools;

use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ModulePresetsModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/module_presets';

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function form(Schema $schema): Schema
    {


        $request = request()->all();
        $params = $this->params ?? [];
        if ($request) {
            $params = array_merge($params, $request);
        }

        return $schema
            ->schema([
                View::make('microweber-live-edit::editor-tools.render-module-presets')
                    ->viewData(['params' => $params])

            ]);
    }
}
