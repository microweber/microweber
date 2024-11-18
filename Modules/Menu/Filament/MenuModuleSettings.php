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
        $menuName = $this->getOption('menu_name')
            ?? $this->params['data-name']
            ?? $this->params['menu_name']
            ?? $this->params['name']
            ?? 'header_menu';


        $menu_filter = [];
        $menu = get_menus('make_on_not_found=1&one=1&limit=1&title=' . $menuName);

        $livewireParams = [];
        if(isset($menu['id'])){
            $livewireParams['menu_id'] = $menu['id'];

        }
        $livewireParams['option_group'] = $optionGroup;
        $livewireParams['option_key'] = 'menu_name';
        return $form
            ->schema([
                Tabs::make('Layout settings')
                    ->tabs([
                        Tabs\Tab::make('Layout settings')
                            ->schema([
                                Livewire::make(MenusList::class,$livewireParams),
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
