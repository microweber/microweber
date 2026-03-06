<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class AddContentModalPage extends LiveEditModuleSettings
{
    public string $module = 'editor/add_content_modal';

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                View::make('admin::layouts.partials.add-content-buttons')


            ]);
    }
}
