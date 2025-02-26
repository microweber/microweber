<?php

namespace Modules\Multilanguage\Filament\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Multilanguage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'modules.multilanguage::filament.pages.multilanguage';

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
                Section::make('Multilanguage Settings')
                    ->columns(2)
                    ->schema([
                        Checkbox::make('is_active')
                            ->label('Multilanguage is active?')
                            ->columnSpan(1),

                        Select::make('homepage_language')
                            ->label('Homepage language')
                            ->options($this->getSupportedLanguages())
                            ->placeholder('Select Language')
                            ->columnSpan(1),

                        Checkbox::make('add_prefix_for_all_languages')
                            ->label('Add prefix for all languages')
                            ->columnSpan(1),

                        Checkbox::make('use_geolocation')
                            ->label('Switch language by IP Geolocation')
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
                            ->columnSpan(1),

                        TextInput::make('ipstack_api_access_key')
                            ->label('IpStack.com API Access Key')
                            ->visible(fn (callable $get) => $get('geolocation_provider') === 'ipstack_com')
                            ->columnSpan(1),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        save_option('is_active', $data['is_active'] ? 'y' : 'n', 'multilanguage_settings');
        save_option('homepage_language', $data['homepage_language'], 'website');
        save_option('add_prefix_for_all_languages', $data['add_prefix_for_all_languages'] ? 'y' : 'n', 'multilanguage_settings');
        save_option('use_geolocation', $data['use_geolocation'] ? 'y' : 'n', 'multilanguage_settings');
        save_option('geolocation_provider', $data['geolocation_provider'], 'multilanguage_settings');
        save_option('ipstack_api_access_key', $data['ipstack_api_access_key'], 'multilanguage_settings');

        $this->notify('success', 'Settings saved successfully');
    }

    private function getSupportedLanguages(): array
    {
        $langs = [];
        $langs['none'] = 'None';

        foreach (get_supported_languages(1) as $supported_language) {
            $langs[$supported_language['locale']] = $supported_language['language'] . ' [' . $supported_language['locale'] . ']';
        }

        return $langs;
    }
}
