<?php

namespace Modules\Multilanguage\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Livewire\Livewire;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Multilanguage\Filament\Pages\MultilanguageSettingsAdmin;
use Modules\Multilanguage\Livewire\LanguagesTable;

class MultilanguageSettings extends LiveEditModuleSettings
{
    public string $module = 'multilanguage';
    public string $optionGroup = 'multilanguage_settings';

    public array $optionGroups = [
        'multilanguage_settings',
        'website'
    ];

    public function form(Schema $schema): Schema
    {
        $langs = [];
        foreach (get_supported_languages(1) as $supported_language) {
            $langs[$supported_language['locale']] = $supported_language['language'] . ' [' . $supported_language['locale'] . ']';
        }

        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->schema([
                        Tabs\Tab::make('Languages')
                            ->schema([
                                Actions::make([

                                    Action::make('Edit languages')
                                        ->openUrlInNewTab()
                                        ->label('Edit Languages')
                                        ->icon('heroicon-o-globe-alt')
                                        ->url(MultilanguageSettingsAdmin::getUrl(), shouldOpenInNewTab: true),

                                ]),

//                                Toggle::make('options.multilanguage.is_active')
//                                    ->label('Multilanguage is active')
//                                    ->helperText('Enable or disable multilanguage functionality for your website')
//                                    ->live(),
//
//
//                                Section::make('Manage Languages')
//                                    ->visible(fn(Get $get) => $get('options.multilanguage.is_active') === true)
//
//                                    ->schema([
//                                        \Filament\Forms\Components\Livewire::make(LanguagesTable::class)
//                                    ]),
                            ]),


                        // Add template settings
                        Tabs\Tab::make('Templates')
                            ->schema($this->getTemplatesFormSchema()),
                    ])
            ]);
    }
}
