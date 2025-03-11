<?php

namespace Modules\Multilanguage\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales;
use Modules\Multilanguage\Models\Language;

class LanguagesTable extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public $selectedLanguageCode = null;
    public $selectedLocale = null;



    public function table(Table $table): Table
    {

        $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();

        return $table
            ->query(MultilanguageSupportedLocales::query())
            ->reorderable()
            ->columns([
                TextColumn::make('locale')
                    ->label('Locale')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('language')
                    ->label('Language')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('display_name')
                    ->label('Language Name')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
            ])
            ->filters([
                // You can add filters here if needed
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Select::make('language')
                            ->required()
                            ->searchable()
                            ->preload(true)
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // When language changes, update the locale options
                                $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                foreach ($languages as $language) {
                                    if ($language['language'] === $state) {
                                        $set('locale', $language['locale']);
                                        break;
                                    }
                                }
                            })
                            ->options(function () {
                                $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                $options = [];
                                foreach ($languages as $language) {
                                    $options[$language['language']] = $language['name'];
                                }
                                return $options;
                            }),
                        Select::make('locale')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->preload(true)
                            ->native(false)
                            ->options(function (callable $get) {
                                $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                $options = [];
                                $selectedLanguage = $get('language');

                                if ($selectedLanguage) {
                                    // Filter locales based on selected language
                                    foreach ($languages as $language) {
                                        if ($language['language'] === $selectedLanguage) {
                                            $options[$language['locale']] = $language['locale'] . ' (' . $language['name'] . ')';

                                            // Add all available locales for this language
                                            if (isset($language['locales']) && is_array($language['locales'])) {
                                                foreach ($language['locales'] as $keyLocale => $locale) {
                                                    $options[$keyLocale] = $locale . ' (' . $language['name'] . ')';
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // If no language selected, show all locales
                                    foreach ($languages as $language) {
                                        $options[$language['locale']] = $language['locale'] . ' (' . $language['name'] . ')';
                                    }
                                }

                                return $options;
                            }),
                        Toggle::make('is_active')
                            ->label('Active'),
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Language updated')
                            ->body('The language has been updated successfully.')
                    ),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Language deleted')
                            ->body('The language has been deleted successfully.')
                    ),
            ])
            ->bulkActions([
                // You can add bulk actions here if needed
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add Language')
                    ->form([
                        Select::make('language')
                            ->required()
                            ->reactive()
                            ->preload(true)
                            ->native(false)
                            ->searchable()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // When language changes, update the locale options
                                $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                foreach ($languages as $language) {
                                    if ($language['language'] === $state) {
                                        $set('locale', $language['locale']);
                                        break;
                                    }
                                }
                            })
                            ->options(function () {
                                $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                $options = [];
                                foreach ($languages as $language) {
                                    $options[$language['language']] = $language['name'];
                                }
                                return $options;
                            }),
                        Select::make('locale')
                            ->required()
                            ->reactive()
                            ->preload(true)
                            ->native(false)
                            ->searchable()
                            ->options(function (callable $get) {
                                $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                $options = [];
                                $selectedLanguage = $get('language');

                                if ($selectedLanguage) {
                                    // Filter locales based on selected language
                                    foreach ($languages as $language) {
                                        if ($language['language'] === $selectedLanguage) {
                                            $options[$language['locale']] = $language['locale'] . ' (' . $language['name'] . ')';

                                            // Add all available locales for this language
                                            if (isset($language['locales']) && is_array($language['locales'])) {
                                                foreach ($language['locales'] as $locale) {
                                                    $options[$locale] = $locale . ' (' . $language['name'] . ')';
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // If no language selected, show all locales
                                    foreach ($languages as $language) {
                                        $options[$language['locale']] = $language['locale'] . ' (' . $language['name'] . ')';
                                    }
                                }


                                return $options;
                            }),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->action(function (array $data): void {
                        $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();

                        // Find the language name based on the language code
                        $languageName = null;
                        foreach ($languages as $language) {
                            if ($language['language'] === $data['language']) {
                                $languageName = $language['name'];
                                break;
                            }
                        }

                        // Add the language name to the data
                        if ($languageName) {
                            $data['display_name'] = $languageName;
                        }

                        MultilanguageSupportedLocales::create($data);

                        Notification::make()
                            ->success()
                            ->title('Language created')
                            ->body('The language has been created successfully.')
                            ->send();
                    }),
            ]);
    }

    public function render(): View
    {
        return view('modules.multilanguage::livewire.languages-table');
    }

}
