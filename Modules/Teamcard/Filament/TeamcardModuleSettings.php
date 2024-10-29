<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TeamcardModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'teamcard';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Teamcard')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(TeamcardTableList::class, [
                                    'moduleId' => $this->params['id']
                                ])
                            ]),

                        Tabs\Tab::make('Design')
                            ->schema([

                            ]),
                    ]),
            ]);
    }

}
