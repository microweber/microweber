<?php

namespace Modules\Multilanguage\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Livewire\Livewire;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Multilanguage\Livewire\LanguagesTable;

class MultilanguageSettings extends LiveEditModuleSettings
{
    public string $module = 'multilanguage';

    public function form(Form $form): Form
    {
        $langs = [];
        foreach (get_supported_languages(1) as $supported_language) {
            $langs[$supported_language['locale']] = $supported_language['language'] . ' [' . $supported_language['locale'] . ']';
        }

        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Languages')
                            ->schema([
                                Section::make('Manage Languages')
                                    ->schema([
                                        \Filament\Forms\Components\Livewire::make(LanguagesTable::class)
                                    ]),
                            ]),



                        // Add template settings
                        Tabs\Tab::make('Templates')
                            ->schema($this->getTemplatesFormSchema()),
                    ])
            ]);
    }
}
