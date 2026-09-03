<?php

namespace Modules\Captcha\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CaptchaModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'captcha';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('options.provider')
                    ->label(__('Captcha provider'))
                    ->options([
                        'microweber' => __('Captcha'),
                        'google_recaptcha_v2' => __('Google ReCaptcha V2'),
                        'google_recaptcha_v3' => __('Google ReCaptcha V3'),
                    ])
                    ->default(fn () => $this->getOption('provider', 'microweber'))
                    ->live(),

                TextInput::make('options.recaptcha_v2_site_key')
                    ->label(__('Google Recaptcha V2 Site Key'))
                    ->visible(fn ($get) => $get('options.provider') === 'google_recaptcha_v2'),

                TextInput::make('options.recaptcha_v2_secret_key')
                    ->label(__('Google ReCaptcha V2 Secret Key'))
                    ->live()
                    ->visible(fn ($get) => $get('options.provider') === 'google_recaptcha_v2'),

                TextInput::make('options.recaptcha_v3_site_key')
                    ->label(__('Google Recaptcha V3 Site Key'))
                    ->live()
                    ->default(fn () => $this->getOption('recaptcha_v3_site_key', ''))
                    ->visible(fn ($get) => $get('options.provider') === 'google_recaptcha_v3'),

                TextInput::make('options.recaptcha_v3_secret_key')
                    ->label(__('Google ReCaptcha V3 Secret Key'))
                    ->live()
                    ->visible(fn ($get) => $get('options.provider') === 'google_recaptcha_v3'),

                TextInput::make('options.recaptcha_v3_score')
                    ->label(__('Google ReCaptcha V3 Score'))
                    ->placeholder('0.5')
                    ->live()
                    ->default(fn () => $this->getOption('recaptcha_v3_score', '0.5'))
                    ->visible(fn ($get) => $get('options.provider') === 'google_recaptcha_v3'),
            ]);
    }
}
