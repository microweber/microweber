<?php

namespace Modules\Multilanguage\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use Modules\Multilanguage\Livewire\LanguagesTable;

class MultilanguageSettingsAdmin extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-language';

    protected string $view = 'modules.multilanguage::filament.pages.multilanguage-settings-admin';

    protected static ?string $title = 'Multilanguage';

    protected static string $description = 'Configure multilanguage settings for your website';

    protected static string | \UnitEnum | null $navigationGroup = 'Language Settings';

    protected static ?int $navigationSort = 2000;

    public string $module = 'multilanguage';
    public string $optionGroup = 'multilanguage_settings';

    public array $optionGroups = [
        'multilanguage_settings',
        'website'
    ];


    protected function getHeaderActions(): array
    {
        return [
            Action::make('test_geo_api')
                ->label('Test Geo API')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->action(function () {
                    $this->testGeoApi();
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $langs = ['none' => 'None'];
        $availableLanguages= [];
        foreach (get_supported_languages(1) as $supported_language) {
             $langs[$supported_language['locale']] = $supported_language['display_name'] . ' [' . $supported_language['locale'] . ']';
            $availableLanguages[$supported_language['locale']] = $supported_language['display_name'] . ' [' . $supported_language['locale'] . ']';
        }




        return $schema
            ->schema([


                Tabs::make('Multilanguage Settings')
                    ->schema([
                        Tabs\Tab::make('Languages')
                            ->schema([

                                Toggle::make('options.multilanguage_settings.is_active')
                                    ->label('Activate multilanguage')
                                    ->helperText('Enable or disable multilanguage functionality for your website')
                                    ->live(),


                                Section::make('Default Language Settings')
                                    ->description('Set the default language for your website')
                                    ->visible(fn(Get $get) => $get('options.multilanguage_settings.is_active') === true)
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



                                Section::make('Manage Languages')
                                    ->visible(fn(Get $get) => $get('options.multilanguage_settings.is_active') === true)
                                    ->description('Add, edit, and manage the languages available on your website.')
                                    ->schema([
                                        Livewire::make(LanguagesTable::class)
                                    ]),
                            ]),

                        Tabs\Tab::make('General Settings')
                            ->visible(fn(Get $get) => $get('options.multilanguage_settings.is_active') === true)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Basic Settings')
                                            ->schema([


                                                Select::make('options.website.homepage_language')
                                                    ->label('Homepage language')
                                                    ->helperText('Select the default language for your website homepage')
                                                    ->options($langs)
                                                    ->placeholder('Select Language')
                                                    ->live(),

                                                Toggle::make('options.multilanguage_settings.add_prefix_for_all_languages')
                                                    ->label('Add prefix for all languages')
                                                    ->helperText('Add language prefix to URLs for all languages including the default one')
                                                    ->live(),
                                            ]),

                                        Section::make('Geolocation Settings')
                                            ->description('Automatically detect visitor location and switch language accordingly')
                                            ->schema([
                                                Toggle::make('options.multilanguage_settings.use_geolocation')
                                                    ->label('Switch language by IP Geolocation')
                                                    ->helperText('Automatically detect and switch language based on visitor location')
                                                    ->live(),

                                                Select::make('options.multilanguage_settings.geolocation_provider')
                                                    ->label('Geolocation Provider')
                                                    ->helperText('Choose your preferred geolocation IP detector')
                                                    ->default(function (Get $get) {
                                                        return $get('options.multilanguage_settings.geolocation_provider') ?: 'browser_detection';
                                                    })
                                                    ->options([
                                                        'browser_detection' => 'Browser Detection',
                                                        'domain_detection' => 'Domain Detection',
                                                        'geoip_browser_detection' => 'GEO-IP + Browser Detection',
                                                        'microweber' => 'Microweber Geo Api',
                                                        'ipstack_com' => 'IpStack.com',
                                                    ])
                                                    ->live(),

                                                TextInput::make('options.multilanguage_settings.ipstack_api_access_key')
                                                    ->label('IpStack.com API Access Key')
                                                    ->helperText(function () {
                                                        return new HtmlString('Required only if using IpStack.com as geolocation provider. <a href="https://ipstack.com/" target="_blank">Get your API key here</a>');
                                                    })
                                                    ->placeholder('Enter your IpStack API key')
                                                    ->password()
                                                    ->revealable()
                                                    ->visible(fn(Get $get) => $get('options.multilanguage_settings.geolocation_provider') === 'ipstack_com')
                                                    ->live(),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Advanced')
                            ->visible(fn(Get $get) => $get('options.multilanguage_settings.is_active') === true)
                            ->schema([
                                Section::make('Advanced Configuration')
                                    ->description('Advanced multilanguage configuration options')
                                    ->schema([
                                        Actions::make([
                                            FormAction::make('test_geo_api')
                                                ->label('Test Geo API')
                                                ->icon('heroicon-o-globe-alt')
                                                ->color('info')
                                                ->action(function () {
                                                    $this->testGeoApi();
                                                }),

                                            FormAction::make('clear_cache')
                                                ->label('Clear Language Cache')
                                                ->icon('heroicon-o-trash')
                                                ->color('warning')
                                                ->requiresConfirmation()
                                                ->action(function () {
                                                    $this->clearLanguageCache();
                                                }),
                                        ])
                                            ->alignCenter(),

                                        Section::make('Debug Information')
                                            ->schema([
                                                \Filament\Forms\Components\Placeholder::make('current_language')
                                                    ->label('Current Language')
                                                    ->content(fn() => mw()->lang_helper->current_lang()),

                                                \Filament\Forms\Components\Placeholder::make('default_language')
                                                    ->label('Default Language')
                                                    ->content(fn() => mw()->lang_helper->default_lang()),

                                                \Filament\Forms\Components\Placeholder::make('supported_languages_count')
                                                    ->label('Supported Languages Count')
                                                    ->content(fn() => count(get_supported_languages(true))),
                                            ])
                                            ->columns(3),
                                    ]),
                            ]),
                    ])
            ]);
    }

    public function testGeoApi(): void
    {
        try {
            // Create a proper request instance
            $request = request();

            // Call the geolocation test API
            $controller = new \Modules\Multilanguage\Http\Controllers\MultilanguageApiController();
            $response = $controller->geolocationTest($request);

            $responseData = $response->getData(true);

            $message = "Geo API Test Results:\n\n";
            $message .= "Provider: " . ($responseData['provider'] ?? $this->options['multilanguage_settings.geolocation_provider'] ?? 'Not set') . "\n";
            $message .= "Status: " . ($responseData['success'] ?? false ? 'Success' : 'Failed') . "\n";
            $message .= "IP Address: " . ($responseData['ip'] ?? 'Unknown') . "\n";

            if (isset($responseData['detected_languages'])) {
                $message .= "Detected Languages: " . implode(', ', $responseData['detected_languages']) . "\n";
            }

            if (isset($responseData['detected_country_code'])) {
                $message .= "Detected Country: " . $responseData['detected_country_code'] . "\n";
            }

            if (isset($responseData['ipstack_error'])) {
                $message .= "IPStack Error: " . $responseData['ipstack_error'] . "\n";
            }

            Notification::make()
                ->title('Geo API Test Completed')
                ->body($message)
                ->success()
                ->persistent()
                ->send();

            // Dispatch the full results to frontend for detailed view
            $this->dispatch('show-geo-results', $responseData);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Geo API Test Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clearLanguageCache(): void
    {
        try {
            // Clear language-related cache
            if (function_exists('mw_clear_cache')) {
                mw_clear_cache();
            }

            // Clear specific multilanguage cache if exists
            \Cache::forget('multilanguage_supported_locales');
            \Cache::forget('multilanguage_current_lang');

            Notification::make()
                ->title('Language cache cleared')
                ->body('All language-related cache has been cleared successfully.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Cache clear failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
