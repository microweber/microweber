<?php

namespace Modules\Multilanguage\Filament\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Modules\Multilanguage\Livewire\LanguagesTable;
use Modules\Multilanguage\Models\Language;

class Multilanguage extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'modules.multilanguage::filament.pages.multilanguage';
    protected static ?int $navigationSort = 9000;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'is_active' => get_option('is_active', 'multilanguage_settings') == 'y',
            'homepage_language' => get_option('homepage_language', 'website') ?: 'en',
            'add_prefix_for_all_languages' => get_option('add_prefix_for_all_languages', 'multilanguage_settings') == 'y',
            'use_geolocation' => get_option('use_geolocation', 'multilanguage_settings') == 'y',
            'geolocation_provider' => get_option('geolocation_provider', 'multilanguage_settings') ?: 'browser_detection',
            'ipstack_api_access_key' => get_option('ipstack_api_access_key', 'multilanguage_settings'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Multilanguage')
                    ->tabs([

                        Tab::make('Languages')
                            ->schema([
                                Livewire::make(LanguagesTable::class)
                            ]),


                        Tab::make('Settings')
                            ->schema([

                                        Toggle::make('is_active')
                                            ->label('Multilanguage is active?')
                                            ->live()
                                            ->afterStateUpdated(function($state) {
                                                save_option('is_active', $state ? 'y' : 'n', 'multilanguage_settings');
                                                $this->notify('success', 'Setting updated');
                                            })
                                            ->columnSpan(1),

                                        Select::make('homepage_language')
                                            ->label('Homepage language')
                                            ->options($this->getSupportedLanguages())
                                            ->placeholder('Select Language')
                                            ->live()
                                            ->afterStateUpdated(function($state) {
                                                save_option('homepage_language', $state, 'website');
                                                $this->notify('success', 'Setting updated');
                                            })
                                            ->columnSpan(1),

                                        Toggle::make('add_prefix_for_all_languages')
                                            ->label('Add prefix for all languages')
                                            ->live()
                                            ->afterStateUpdated(function($state) {
                                                save_option('add_prefix_for_all_languages', $state ? 'y' : 'n', 'multilanguage_settings');
                                                $this->notify('success', 'Setting updated');
                                            })
                                            ->columnSpan(1),

                                        Toggle::make('use_geolocation')
                                            ->label('Switch language by IP Geolocation')
                                            ->live()
                                            ->afterStateUpdated(function($state) {
                                                save_option('use_geolocation', $state ? 'y' : 'n', 'multilanguage_settings');
                                                $this->notify('success', 'Setting updated');
                                            })
                                            ->columnSpan(1),

                                        Select::make('geolocation_provider')
                                            ->label('Geolocation Provider')
                                            ->helperText('Choose your preferred geolocation IP detector')
                                            ->options([
                                                'browser_detection' => 'Browser Detection',
                                                'domain_detection' => 'Domain Detection',
                                                'geoip_browser_detection' => 'GEO-IP + Browser Detection',
                                                'microweber' => 'Microweber Geo Api',
                                                'ipstack_com' => 'IpStack.com',
                                            ])
                                            ->live()
                                            ->afterStateUpdated(function($state) {
                                                save_option('geolocation_provider', $state, 'multilanguage_settings');
                                                $this->notify('success', 'Setting updated');
                                            })
                                            ->columnSpan(1),

                                        TextInput::make('ipstack_api_access_key')
                                            ->label('IpStack.com API Access Key')
                                            ->visible(fn (callable $get) => $get('geolocation_provider') === 'ipstack_com')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function($state) {
                                                save_option('ipstack_api_access_key', $state, 'multilanguage_settings');
                                                $this->notify('success', 'API key updated');
                                            })
                                            ->columnSpan(1),

                                        \Filament\Forms\Components\Actions::make([
                                            \Filament\Forms\Components\Actions\Action::make('testGeoApi')
                                                ->label('Test Geo API')
                                                ->extraAttributes(['onclick' => 'window.testGeoApi(); return false;'])
                                        ])
                                ]),
                    ])
            ])
            ->statePath('data');
    }

    protected function notify(string $status, string $message): void
    {
        Notification::make()
            ->title($message)
            ->status($status)
            ->send();
    }

    public function testGeoApi(): void
    {
        // This method will be called from the button in the template
        $this->notify('info', 'Testing Geo API...');

        // You could add additional logic here if needed
    }

    private function getSupportedLanguages(): array
    {
        $langs = [];
        $langs['none'] = 'None';
        $languages = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();

        foreach (get_supported_languages(1) as $supported_language) {
            $langs[$supported_language['locale']] = $supported_language['language'] . ' [' . $supported_language['locale'] . ']';
        }

        return $langs;
    }
}
