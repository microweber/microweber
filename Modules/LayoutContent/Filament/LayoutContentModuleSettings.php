<?php

namespace Modules\LayoutContent\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\LayoutContent\Models\LayoutContentItem;

class LayoutContentModuleSettings extends LiveEditModuleSettingsTable
{
    public string $module = 'layout_content';

    public string $modelName = LayoutContentItem::class;
    public string $tableComponentName = LayoutContentTableList::class;

    public function form(Form $form): Form
    {
        $moduleId = $this->params['id'] ?? null;
        $relId = $this->getOption('rel_id') ?? $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $relType = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';

        return $form
            ->schema([
                Tabs::make('Layout Content')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make($this->tableComponentName, [
                                    'rel_id' => $relId,
                                    'rel_type' => $relType,
                                    'module_id' => $moduleId,
                                ])
                                    ->reactive()
                                    ->live(),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),

                        Tabs\Tab::make('Advanced')
                            ->schema($this->getDataSourceFormSchema()),

                    ]),
            ]);
    }
}
