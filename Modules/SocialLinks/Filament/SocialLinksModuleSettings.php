<?php

namespace Modules\SocialLinks\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SocialLinksModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'social_links';

    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Content')
                            ->schema([


                                Toggle::make('options.facebook_enabled')
                                    ->label('Enable Facebook Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.facebook_url')
                                    ->label('Facebook URL')
                                    ->live()
                                    ->placeholder('Enter Facebook URL')
                                    ->visible(fn($get) => $get('options.facebook_enabled')),

                                Toggle::make('options.twitter_enabled')
                                    ->label('Enable Twitter Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.twitter_url')
                                    ->label('Twitter URL')
                                    ->live()
                                    ->placeholder('Enter Twitter URL')
                                    ->visible(fn($get) => $get('options.twitter_enabled')),

                                Toggle::make('options.pinterest_enabled')
                                    ->label('Enable Pinterest Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.pinterest_url')
                                    ->label('Pinterest URL')
                                    ->live()
                                    ->placeholder('Enter Pinterest URL')
                                    ->visible(fn($get) => $get('options.pinterest_enabled')),

                                Toggle::make('options.linkedin_enabled')
                                    ->label('Enable LinkedIn Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->live()
                                    ->placeholder('Enter LinkedIn URL')
                                    ->visible(fn($get) => $get('options.linkedin_enabled')),

                                Toggle::make('options.viber_enabled')
                                    ->label('Enable Viber Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.viber_url')
                                    ->label('Viber URL')
                                    ->live()
                                    ->placeholder('Enter Viber URL')
                                    ->visible(fn($get) => $get('options.viber_enabled')),

                                Toggle::make('options.whatsapp_enabled')
                                    ->label('Enable WhatsApp Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.whatsapp_url')
                                    ->label('WhatsApp URL')
                                    ->live()
                                    ->placeholder('Enter WhatsApp URL')
                                    ->visible(fn($get) => $get('options.whatsapp_enabled')),

                                Toggle::make('options.telegram_enabled')
                                    ->label('Enable Telegram Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.telegram_url')
                                    ->label('Telegram URL')
                                    ->live()
                                    ->placeholder('Enter Telegram URL')
                                    ->visible(fn($get) => $get('options.telegram_enabled')),


                            ]),

                        Tabs\Tab::make('Design')
                            ->schema([
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()),
                            ]),
                    ]),
            ]);
    }
}
