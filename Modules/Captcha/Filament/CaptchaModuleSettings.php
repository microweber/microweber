<?php

namespace Modules\Captcha\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CaptchaModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'captcha';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('options.provider')
                    ->label(__('Captcha provider'))
                    ->options([
                        'microweber' => __('Captcha'),
                        'google_recaptcha_v2' => __('Google ReCaptcha V2'),
                        'google_recaptcha_v3' => __('Google ReCaptcha V3'),
                    ])
                    ->reactive(),

                TextInput::make('options.recaptcha_v2_site_key')
                    ->label(__('Google Recaptcha V2 Site Key'))
                    ->visible(fn ($get) => $get('provider') === 'google_recaptcha_v2'),

                TextInput::make('options.recaptcha_v2_secret_key')
                    ->label(__('Google ReCaptcha V2 Secret Key'))
                    ->visible(fn ($get) => $get('provider') === 'google_recaptcha_v2'),

                TextInput::make('options.recaptcha_v3_site_key')
                    ->label(__('Google Recaptcha V3 Site Key'))
                    ->visible(fn ($get) => $get('provider') === 'google_recaptcha_v3'),

                TextInput::make('options.recaptcha_v3_secret_key')
                    ->label(__('Google ReCaptcha V3 Secret Key'))
                    ->visible(fn ($get) => $get('provider') === 'google_recaptcha_v3'),

                TextInput::make('options.recaptcha_v3_score')
                    ->label(__('Google ReCaptcha V3 Score'))
                    ->placeholder('0.5')
                    ->visible(fn ($get) => $get('provider') === 'google_recaptcha_v3'),
            ]);
    }
}
