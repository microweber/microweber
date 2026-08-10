<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use Modules\Multilanguage\Filament\Pages\MultilanguageSettingsAdmin;

class AdminLanguagePage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-language';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Language';
    protected static string | \UnitEnum | null $navigationGroup = 'Language Settings';

    protected static string $description = 'Configure your language settings';

    public array $optionGroups = [
        'website'
    ];

    public static function getNavigation(): array
    {
        return [
            'label' => static::$title,
            'icon' => 'heroicon-o-language',
            'url' => static::getUrl(),
            'group' => 'Settings',
            'sort' => 1,
            'iconHtml' => '<i class="mw-language" style="font-size: 30px;"></i>', // Customize font size here
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        // Import/Export actions for translations
        $actions[] = Action::make('import_translations')
            ->label('Import Translations')
            ->icon('heroicon-o-arrow-down-tray')
            ->modal()
            ->modalHeading('Import Language File')
            ->modalDescription('Upload a .xlsx or .json file to import translations')
            ->modalSubmitActionLabel('Import')
            ->form([
                Select::make('locale')
                    ->label('Target Language')
                    ->options(function () {
                        $supportedLanguages = [];
                        if (function_exists('get_supported_languages')) {
                            $languages = get_supported_languages(true);
                            foreach ($languages as $language) {
                                $supportedLanguages[$language['locale']] = strtoupper($language['locale']) . ' - ' . $language['language'];
                            }
                        } else {
                            // Default language fallback
                            $defaultLang = function_exists('mw') ? app()->lang_helper->default_lang() : 'en_US';
                            $supportedLanguages[$defaultLang] = strtoupper($defaultLang) . ' - Default';
                        }
                        return $supportedLanguages;
                    })
                    ->required()
                    ->helperText('Select the language to import translations for'),

                \Filament\Forms\Components\FileUpload::make('translation_file')
                    ->label('Translation File')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/json', '.xlsx', '.json'])
                    ->required()
                    ->helperText('Upload a .xlsx or .json file containing translations')
                    ->directory('temp/translations')
                    ->visibility('private'),

                Toggle::make('replace_values')
                    ->label('Replace existing values')
                    ->helperText('If enabled, existing translations will be overwritten')
                    ->default(false),
            ])
            ->action(function (array $data) {
                return $this->handleImport($data);
            });

        $actions[] = Action::make('export_translations')
            ->label('Export Translations')
            ->icon('heroicon-o-arrow-up-tray')
            ->modal()
            ->modalHeading('Export Language File')
            ->modalDescription('Export translations to a file')
            ->modalSubmitActionLabel('Export')
            ->form([
                Select::make('locale')
                    ->label('Language to export')
                    ->options(function () {
                        $supportedLanguages = [];
                        if (function_exists('get_supported_languages')) {
                            $languages = get_supported_languages(true);
                            foreach ($languages as $language) {
                                $supportedLanguages[$language['locale']] = strtoupper($language['locale']) . ' - ' . $language['language'];
                            }
                        } else {
                            // Default language fallback
                            $defaultLang = function_exists('mw') ? app()->lang_helper->default_lang() : 'en_US';
                            $supportedLanguages[$defaultLang] = strtoupper($defaultLang) . ' - Default';
                        }
                        return $supportedLanguages;
                    })
                    ->required(),

                Select::make(\MicroweberPackages\Format\FormatService::class)
                    ->label('Export format')
                    ->options([
                        'xlsx' => '.xlsx (Excel)',
                        'json' => '.json'
                    ])
                    ->default('xlsx')
                    ->required(),

                Select::make('namespace')
                    ->label('Translation namespace')
                    ->options(function () {
                        $options = [
                            '*' => 'All translations',
                            'global' => 'Global translations',
                        ];

                        // Get dynamic namespaces
                        if (class_exists('\MicroweberPackages\Translation\Models\TranslationKey')) {
                            $namespaces = \MicroweberPackages\Translation\Models\TranslationKey::getNamespaces();
                            foreach ($namespaces as $ns => $nsData) {
                                if (!in_array($ns, ['*', 'global']) && !empty($nsData['translation_namespace'])) {
                                    $label = ucfirst(str_replace(['modules-', 'templates-', '-'], ['', '', ' '], $ns));
                                    $options[$nsData['translation_namespace']] = $label . ' (' . $nsData['translation_namespace'] . ')';
                                }
                            }
                        }

                        return $options;
                    })
                    ->default('*')
                    ->required(),
            ])
            ->action(function (array $data) {
                return $this->handleExport($data);
            });

        return $actions;
    }

    protected function handleImport(array $data): void
    {
        try {
            if (empty($data['translation_file'])) {
                Notification::make()
                    ->title('No file selected')
                    ->body('Please select a file to import')
                    ->danger()
                    ->send();
                return;
            }

            // Try different possible file paths
            $possiblePaths = [
                storage_path('app/public/' . $data['translation_file']),
                storage_path('app/public/temp/translations/' . basename($data['translation_file'])),
                storage_path('app/' . $data['translation_file']),
                storage_path('app/temp/translations/' . basename($data['translation_file'])),
            ];

            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }

            if (!$filePath) {
                Notification::make()
                    ->title('File not found')
                    ->body('The uploaded file could not be found. Tried paths: ' . implode(', ', $possiblePaths))
                    ->danger()
                    ->send();
                return;
            }

            // Import using existing controller logic
            if (class_exists('\MicroweberPackages\Translation\Http\Controllers\TranslationController')) {
                $request = new \Illuminate\Http\Request();
                $request->merge([
                    'src' =>$filePath,
                    'locale' => $data['locale'],
                    'replace_values' => $data['replace_values'] ? 1 : 0
                ]);

                $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();
                $result = $controller->import($request);

                if (isset($result['success'])) {
                    Notification::make()
                        ->title('Import successful')
                        ->body($result['success'])
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Import failed')
                        ->body($result['error'] ?? 'Unknown error occurred')
                        ->danger()
                        ->send();
                }
            } else {
                Notification::make()
                    ->title('Import functionality not available')
                    ->body('Translation import controller not found')
                    ->warning()
                    ->send();
            }

            // Clean up uploaded file
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import error')
                ->body('An error occurred during import: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function handleExport(array $data): void
    {
        try {
            // Export using existing controller logic
            if (class_exists('\MicroweberPackages\Translation\Http\Controllers\TranslationController')) {
                $request = new \Illuminate\Http\Request();
                $request->merge($data);

                $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();
                $result = $controller->export($request);

                if (isset($result['files']) && !empty($result['files'])) {
                    $fileInfo = $result['files'][0];
                    if (isset($fileInfo['download'])) {
                        // Trigger download via notification with link
                        Notification::make()
                            ->title('Export ready')
                            ->body('Your translation export is ready for download.')
                            ->success()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('download')
                                    ->label('Download File')
                                    ->url($fileInfo['download'])
                                    ->openUrlInNewTab()
                            ])
                            ->send();
                        return;
                    } elseif (isset($fileInfo['filepath'])) {
                        // Create public download link
                        $filename = basename($fileInfo['filepath']);
                        $publicPath = 'storage/exports/' . $filename;

                        // Copy file to public storage if needed
                        if (!file_exists(public_path($publicPath))) {
                            @mkdir(dirname(public_path($publicPath)), 0755, true);
                            copy($fileInfo['filepath'], public_path($publicPath));
                        }

                        Notification::make()
                            ->title('Export ready')
                            ->body('Your translation export is ready for download.')
                            ->success()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('download')
                                    ->label('Download File')
                                    ->url(url($publicPath))
                                    ->openUrlInNewTab()
                            ])
                            ->send();
                        return;
                    }
                }

                Notification::make()
                    ->title('Export failed')
                    ->body('Could not generate export file')
                    ->danger()
                    ->send();

            } else {
                Notification::make()
                    ->title('Export functionality not available')
                    ->body('Translation export controller not found')
                    ->warning()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Export error')
                ->body('An error occurred during export: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function form(Schema $schema): Schema
    {
        // Get available languages
        $availableLanguages = [];
        if (function_exists('get_supported_languages')) {
            $availableLanguages = get_available_languages();
        }

        // Check if multilanguage is available and activated
        $hasMultilanguageModule = is_module('multilanguage');
        $isMultilanguageActivated = false;
        if ($hasMultilanguageModule && function_exists('get_supported_languages')) {
            $isMultilanguageActivated = MultilanguageHelpers::multilanguageIsEnabled();

        }


        // Check if translations exist in database
        $translationsExist = false;
        if (function_exists('mw') && app()->lang_helper) {
            $currentLang = app()->lang_helper->current_lang();
            $translationsExist = TranslationText::where('translation_locale', $currentLang)->count() > 0;
        }

        return $schema
            ->schema([
                Section::make('Default Language Settings')
                    ->icon('heroicon-m-language')
                    ->view('mw-filament::sections.section')
                    ->description('Set the default language for your website')
                    ->schema([
                        Select::make('options.website.language')
                            ->label('Default Language')
                            ->options($availableLanguages)
                            ->searchable()
                            ->helperText('This will be the default language for your website')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    // Apply language change logic
                                    Notification::make()
                                        ->title('Language settings updated')
                                        ->success()
                                        ->send();
                                }
                            }),
                    ]),

                Section::make('Multilanguage Support')
                    ->icon('heroicon-m-globe-alt')
                    ->view('mw-filament::sections.section')
                    ->description('Enable support for multiple languages on your website')
                    ->schema([
                        Actions::make([
                            Action::make('manage_multilanguage')
                                ->label($hasMultilanguageModule ? 'Manage Multilanguage Settings' : 'Install Multilanguage Module')
                                ->icon($hasMultilanguageModule ? 'heroicon-o-cog-6-tooth' : 'heroicon-o-plus')
                                ->color($hasMultilanguageModule ? 'primary' : 'success')
                                ->url(function () use ($hasMultilanguageModule) {
                                    if ($hasMultilanguageModule && class_exists(MultilanguageSettingsAdmin::class)) {
                                        return MultilanguageSettingsAdmin::getUrl();
                                    }
                                    return '#'; // Install URL would go here
                                }, shouldOpenInNewTab: false)
                                ->visible(true),
                        ]),

                        \Filament\Forms\Components\Placeholder::make('multilanguage_status')
                            ->label('Status')
                            ->content(function () use ($hasMultilanguageModule, $isMultilanguageActivated) {
                                if (!$hasMultilanguageModule) {
                                    return new HtmlString('<span class="text-orange-600">Multilanguage module not installed</span>');
                                }
                                if ($isMultilanguageActivated) {
                                    return new HtmlString('<span class="text-green-600">Multilanguage is active</span>');
                                }
                                return new HtmlString('<span class="text-gray-600">Multilanguage is available but not activated</span>');
                            }),
                    ]),

                Section::make('Translation Management')
                    ->icon('heroicon-m-document-duplicate')
                    ->view('mw-filament::sections.section')
                    ->description('Manage translations for your website content')
                    ->schema([
                        // Quick access to translation manager
                        Actions::make([
                            Action::make('manage_translations')
                                ->label('Manage Translations')
                                ->icon('heroicon-o-language')
                                ->color('primary')
                                ->url(function () {
                                    if (class_exists('\Modules\Settings\Filament\Resources\TranslationResource')) {
                                        return \Modules\Settings\Filament\Resources\TranslationResource::getUrl();
                                    }
                                    return '#';
                                }, shouldOpenInNewTab: false)
                                ->visible(true),

                            Action::make('import_missing_translations')
                                ->label('Import Missing Translations')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->color('info')
                                ->action(function () {
                                    // Import missing translations logic
                                    if (function_exists('get_supported_languages')) {
                                        $supportedLanguages = get_supported_languages();
                                        if (!empty($supportedLanguages)) {
                                            foreach ($supportedLanguages as $supportedLanguage) {
                                                $translationsCount = TranslationText::where('translation_locale', $supportedLanguage['locale'])->count();
                                                if ($translationsCount == 0) {
                                                    if (class_exists('\MicroweberPackages\Translation\TranslationPackageInstallHelper')) {
                                                        \MicroweberPackages\Translation\TranslationPackageInstallHelper::installLanguage($supportedLanguage['locale']);
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    Notification::make()
                                        ->title('Missing translations imported')
                                        ->success()
                                        ->send();
                                })
                                ->visible(!$translationsExist),
                        ]),

                        // Translation status information
                        \Filament\Forms\Components\Placeholder::make('translation_info')
                            ->label('Translation Status')
                            ->content(function () use ($translationsExist) {
                                if ($translationsExist) {
                                    $totalKeys = TranslationKey::count();
                                    $totalTranslations = TranslationText::count();
                                    return new HtmlString("
                                        <div class='space-y-2'>
                                            <div>Total translation keys: <strong>{$totalKeys}</strong></div>
                                            <div>Total translations: <strong>{$totalTranslations}</strong></div>
                                            <div class='text-sm text-gray-600'>Use the 'Manage Translations' button above to add, edit, or delete translations.</div>
                                        </div>
                                    ");
                                }
                                return new HtmlString('<div class="text-gray-600">No translations found. Import missing translations to get started.</div>');
                            }),

                        // Help section
                        \Filament\Forms\Components\Placeholder::make('translation_help')
                            ->label('Need Help?')
                            ->content(function () {
                                $helpContent = 'Translation help resources:<br>';
                                $helpContent .= '• Use the Translation Manager to edit individual translations<br>';
                                $helpContent .= '• Export translations to work offline and import them back<br>';
                                $helpContent .= '• Each language can be managed separately<br>';
                                if (config('app.debug', false)) {
                                    $helpContent .= '• Check the official documentation for advanced translation features';
                                }
                                return new HtmlString($helpContent);
                            })
                            ->visible($translationsExist),
                    ]),
            ]);
    }
}
