<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;


/* @deprecated  This class is deprecated and will be removed in future versions. Use the new LiveEditModuleSettings class instead. */
class FontsManagerModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/fonts/font-manager-modal';

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                View::make('microweber-live-edit::editor-tools.render-font-manager-component')

            ]);
    }
}
