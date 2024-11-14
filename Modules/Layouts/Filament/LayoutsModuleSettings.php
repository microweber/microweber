<?php

namespace Modules\Layouts\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class LayoutsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'layouts';


    public function form(Form $form): Form
    {

       $optionGroup = $this->getOptionGroup();
        return $form
            ->schema([
                Tabs::make('Layout settings')
                    ->tabs([
                        Tabs\Tab::make('Layout settings')
                            ->schema([
                                View::make('modules.layouts::admin.settings')->viewData([
'optionGroup' => $optionGroup
                                ]),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                                    Section::make('Design settings')->schema(
                                        $this->getTemplatesFormSchema())
                                ]
                            ),
                    ]),
            ]);
    }

}
