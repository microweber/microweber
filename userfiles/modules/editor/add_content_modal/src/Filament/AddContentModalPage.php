<?php

namespace MicroweberPackages\Modules\Editor\AddContentModal\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class AddContentModalPage extends LiveEditModuleSettings
{
    public string $module = 'editor/add_content_modal';

    protected static string $view = 'filament-panels::components.layout.simple-form';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                View::make('admin::layouts.partials.add-content-buttons')

            ]);
    }
}
