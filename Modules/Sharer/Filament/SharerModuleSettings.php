<?php

namespace Modules\Sharer\Filament;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SharerModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'sharer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('options.facebook_enabled')
                    ->label('Enable Facebook Sharing')
                    ->live()
                    ->default(false),

                Toggle::make('options.twitter_enabled')
                    ->label('Enable Twitter Sharing')
                    ->live()
                    ->default(false),

                Toggle::make('options.pinterest_enabled')
                    ->label('Enable Pinterest Sharing')
                    ->live()
                    ->default(false),

                Toggle::make('options.linkedin_enabled')
                    ->label('Enable LinkedIn Sharing')
                    ->live()
                    ->default(false),

                Toggle::make('options.viber_enabled')
                    ->label('Enable Viber Sharing')
                    ->live()
                    ->default(false),

                Toggle::make('options.whatsapp_enabled')
                    ->label('Enable WhatsApp Sharing')
                    ->live()
                    ->default(false),

                Toggle::make('options.telegram_enabled')
                    ->label('Enable Telegram Sharing')
                    ->live()
                    ->default(false),


            ]);
    }
}
