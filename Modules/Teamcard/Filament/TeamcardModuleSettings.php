<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Teamcard\Models\Teamcard;

class TeamcardModuleSettings extends LiveEditModuleSettingsTable
{
    public string $module = 'teamcard';
    public string $modelName = Teamcard::class;
    public string $tableComponentName = TeamcardTableList::class;

    public function form(Form $form): Form
    {
        $module_id = $this->params['id'] ?? null;
        $rel_id = $this->getOption('rel_id') ?? $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $rel_type = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';

        return $form
            ->schema([
                Tabs::make('Teamcard')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make($this->tableComponentName, [
                                    'rel_id' => $rel_id,
                                    'rel_type' => $rel_type,
                                    'module_id' => $module_id,
                                ])
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                        Tabs\Tab::make('Advanced')
                            ->schema($this->getDataSourceFormSchema()),
                    ]),
            ]);
    }

}
