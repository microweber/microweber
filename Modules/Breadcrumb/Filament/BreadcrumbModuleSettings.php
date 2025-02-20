<?php

namespace Modules\Breadcrumb\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BreadcrumbModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'breadcrumb';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('options.data-start-from')
                    ->label('Root level')
                    ->options([
                        '' => 'Default',
                        'page' => 'Page',
                        'category' => 'Category',
                    ])
                    ->default('')
                    ->live()
            ]);
    }
}
