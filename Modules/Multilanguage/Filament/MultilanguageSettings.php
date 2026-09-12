<?php

namespace Modules\Multilanguage\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Multilanguage\Filament\Pages\MultilanguageSettingsAdmin;

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

                                Toggle::make('options.multilanguage.is_active')
                                    ->label('Multilanguage is active')
                                    ->helperText('Enable or disable multilanguage functionality for your website')
                                    ->default(fn () => (bool) $this->getOption('multilanguage.is_active', false))
                                    ->live(),
                            ]),


                        // Add template settings
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ])
            ]);
    }
}
