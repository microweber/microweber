<?php

namespace Modules\Menu\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Support\Components\ViewComponent;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Menu\Livewire\Admin\MenusList;

class MenuModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'menu';

    public function form(Form $form): Form
    {

        $optionGroup = $this->getOptionGroup();
        return $form
            ->schema([
                Tabs::make('Layout settings')
                    ->tabs([
                        Tabs\Tab::make('Layout settings')
                            ->schema([
                                Livewire::make(MenusList::class),
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
