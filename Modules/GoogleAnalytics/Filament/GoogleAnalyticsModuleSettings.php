<?php

namespace Modules\GoogleAnalytics\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class GoogleAnalyticsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'googleanalytics';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Google Analytics')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Toggle::make('google-measurement-enabled')
                                    ->label('Enable Google Analytics')
                                    ->helperText('Enable Google Analytics tracking on your website')
                                    ->default(false),

                                TextInput::make('google-measurement-id')
                                    ->label('Measurement ID')
                                    ->helperText('Your Google Analytics 4 Measurement ID (e.g., G-XXXXXXXXXX)')
                                    ->required()
                                    ->visible(fn ($get) => $get('google-measurement-enabled')),

                                TextInput::make('google-measurement-api-secret')
                                    ->label('API Secret')
                                    ->helperText('Your Google Analytics 4 API Secret')
                                    ->required()
                                    ->visible(fn ($get) => $get('google-measurement-enabled')),
                            ]),
                        Tabs\Tab::make('Enhanced Conversions')
                            ->schema([
                                Toggle::make('google-enhanced-conversions-enabled')
                                    ->label('Enable Enhanced Conversions')
                                    ->helperText('Enable Google Analytics Enhanced Conversions tracking')
                                    ->default(false),

                                TextInput::make('google-enhanced-conversion-id')
                                    ->label('Enhanced Conversion ID')
                                    ->helperText('Your Google Analytics Enhanced Conversion ID')
                                    ->required()
                                    ->visible(fn ($get) => $get('google-enhanced-conversions-enabled')),

                                TextInput::make('google-enhanced-conversion-label')
                                    ->label('Enhanced Conversion Label')
                                    ->helperText('Your Google Analytics Enhanced Conversion Label')
                                    ->required()
                                    ->visible(fn ($get) => $get('google-enhanced-conversions-enabled')),
                            ]),
                    ]),
            ]);
    }

    public function getFormData(): array
    {
        return [
            'google-measurement-enabled' => get_option('google-measurement-enabled', 'website') == 'y',
            'google-measurement-id' => get_option('google-measurement-id', 'website'),
            'google-measurement-api-secret' => get_option('google-measurement-api-secret', 'website'),
            'google-enhanced-conversions-enabled' => get_option('google-enhanced-conversions-enabled', 'website') == 'y',
            'google-enhanced-conversion-id' => get_option('google-enhanced-conversion-id', 'website'),
            'google-enhanced-conversion-label' => get_option('google-enhanced-conversion-label', 'website'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        save_option('google-measurement-enabled', $data['google-measurement-enabled'] ? 'y' : 'n', 'website');
        save_option('google-measurement-id', $data['google-measurement-id'], 'website');
        save_option('google-measurement-api-secret', $data['google-measurement-api-secret'], 'website');
        save_option('google-enhanced-conversions-enabled', $data['google-enhanced-conversions-enabled'] ? 'y' : 'n', 'website');
        save_option('google-enhanced-conversion-id', $data['google-enhanced-conversion-id'], 'website');
        save_option('google-enhanced-conversion-label', $data['google-enhanced-conversion-label'], 'website');

        $this->dispatch('settings-saved');
    }
}
