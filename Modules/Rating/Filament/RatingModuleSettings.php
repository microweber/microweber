<?php

namespace Modules\Rating\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Rating\Models\Rating;

class RatingModuleSettings extends LiveEditModuleSettingsTable
{
    public string $module = 'rating';
    public string $modelName = Rating::class;
    public string $tableComponentName = RatingTableList::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Rating')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(RatingTableList::class, [
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
