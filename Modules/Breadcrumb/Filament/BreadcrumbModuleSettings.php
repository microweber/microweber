<?php

namespace Modules\Breadcrumb\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Rating\Filament\RatingTableList;

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

                ,
                Tabs::make('Breadcrumb settings')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);


    }
}
