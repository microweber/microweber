<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools;

use Filament\Forms\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ResetContentModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/reset_content';

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                View::make('microweber-live-edit::editor-tools.reset-content')

            ]);
    }
}
