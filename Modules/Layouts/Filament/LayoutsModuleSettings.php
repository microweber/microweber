<?php

namespace Modules\Layouts\Filament;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class LayoutsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'layouts';


    public function form(Schema $schema): Schema
    {

        $optionGroup = $this->getOptionGroup();
        return $schema
            ->schema([
                Tabs::make('Layout Settings')
                    ->schema([
                        Tabs\Tab::make('Layout Settings')
                            ->schema([
                                View::make('modules.layouts::admin.settings')->viewData([
                                    'optionGroup' => $optionGroup
                                ]),
                            ]),
                        Tabs\Tab::make('Design and Details')
                            ->schema([
                                    Section::make('Design Settings')->schema(
                                        $this->getTemplatesFormSchema())
                                ]
                            ),
                    ]),
            ]);
    }

}
