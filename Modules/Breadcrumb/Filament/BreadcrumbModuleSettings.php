<?php

namespace Modules\Breadcrumb\Filament;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BreadcrumbModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'breadcrumb';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Breadcrumb settings')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Select::make('options.data-start-from')
                                    ->label('Root level')
                                    ->options([
                                        '' => 'Default',
                                        'page' => 'Page',
                                        'category' => 'Category',
                                    ])
                                    ->default('')
                                    ->live(),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);


    }
}
