<?php

namespace Modules\Tabs\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TabsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'tabs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(TabsTableList::class, [
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
