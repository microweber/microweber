<?php

namespace Modules\Accordion\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class AccordionModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'accordion';

    public function form(Form $form): Form
    {

        return $form
            ->schema([

                Tabs::make('Accordion')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(AccordionTableList::class, [
                                    'rel_id' => $this->params['id'] ?? null,
                                    'rel_type' => 'module',
                                ])
                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
