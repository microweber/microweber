<?php

namespace Modules\Faq\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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

    /**
     * Form configuration
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('settings.title')
                    ->label('Title')
                    ->placeholder('FAQ Section Title'),

                Select::make('settings.template')
                    ->label('Template')
                    ->options([
                        'default' => 'Default',
                        'accordion' => 'Accordion',
                        'tabs' => 'Tabs'
                    ])
                    ->default('default'),

                TextInput::make('settings.items_per_page')
                    ->label('Items per page')
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->maxValue(100),
            ]);
    }
}
