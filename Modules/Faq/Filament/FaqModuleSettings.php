<?php

namespace Modules\Faq\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Livewire;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Faq\Models\Faq;

/**
 * FAQ Module Settings
 *
 * Manages the settings and configuration for the FAQ module
 */
class FaqModuleSettings extends LiveEditModuleSettingsTable
{
    /**
     * Module configuration
     */
    public string $module = 'faq';
    public string $modelName = Faq::class;


    public string $tableComponentName = FaqTableList::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Faq')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(FaqTableList::class, [
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
