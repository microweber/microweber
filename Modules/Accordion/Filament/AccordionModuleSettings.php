<?php

namespace Modules\Accordion\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class AccordionModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'accordion';

    public function form(Schema $schema): Schema
    {

        return $schema
            ->schema([

                Tabs::make('Accordion')
                    ->schema([
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
