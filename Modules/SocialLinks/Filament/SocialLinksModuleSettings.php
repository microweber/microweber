<?php

namespace Modules\SocialLinks\Filament;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SocialLinksModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'social_links';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([


                Tabs::make('Settings')
                    ->schema([
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

                                Toggle::make('options.x_enabled')
                                    ->label('Enable X Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.x_url')
                                    ->label('X URL')
                                    ->live()
                                    ->placeholder('Enter X URL')
                                    ->visible(fn($get) => $get('options.x_enabled')),

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

                                Toggle::make('options.youtube_enabled')
                                    ->label('Enable YouTube Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.youtube_url')
                                    ->label('YouTube URL')
                                    ->live()
                                    ->placeholder('Enter YouTube URL')
                                    ->visible(fn($get) => $get('options.youtube_enabled')),

                                Toggle::make('options.instagram_enabled')
                                    ->label('Enable Instagram Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.instagram_url')
                                    ->label('Instagram URL')
                                    ->live()
                                    ->placeholder('Enter instagram URL')
                                    ->visible(fn($get) => $get('options.instagram_enabled')),

                                Toggle::make('options.github_enabled')
                                    ->label('Enable GitHub Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.github_url')
                                    ->label('GitHub URL')
                                    ->live()
                                    ->placeholder('Enter GitHub URL')
                                    ->visible(fn($get) => $get('options.github_enabled')),

                                Toggle::make('options.soundcloud_enabled')
                                    ->label('Enable SoundCloud Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.soundcloud_url')
                                    ->label('SoundCloud URL')
                                    ->live()
                                    ->placeholder('Enter SoundCloud URL')
                                    ->visible(fn($get) => $get('options.soundcloud_enabled')),

                                Toggle::make('options.discord_enabled')
                                    ->label('Enable Discord Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.discord_url')
                                    ->label('Discord URL')
                                    ->live()
                                    ->placeholder('Enter Discord URL')
                                    ->visible(fn($get) => $get('options.discord_enabled')),

                                Toggle::make('options.skype_enabled')
                                    ->label('Enable Skype Sharing')
                                    ->live()
                                    ->default(false),

                                TextInput::make('options.skype_url')
                                    ->label('Skype URL')
                                    ->live()
                                    ->placeholder('Enter Skype URL')
                                    ->visible(fn($get) => $get('options.skype_enabled')),


                            ]),

                        Tabs\Tab::make('Design')
                            ->schema([
                                // task-2026-05-22-759a88 / AI-922 — expose 6 icon style
                                // properties that getSocialNetworksData() already reads
                                // from options but had no UI controls. Operators could
                                // not customize icon appearance via the settings panel.
                                Section::make('Icon Styles')->schema([
                                    ColorPicker::make('options.iconColor')
                                        ->label('Icon Color')
                                        ->live()
                                        ->helperText('Leave blank to use the default icon brand color.'),

                                    ColorPicker::make('options.iconHoverColor')
                                        ->label('Icon Hover Color')
                                        ->live()
                                        ->helperText('Color shown when hovering over an icon.'),

                                    TextInput::make('options.iconSize')
                                        ->label('Icon Size (px)')
                                        ->live()
                                        ->numeric()
                                        ->default('24')
                                        ->helperText('Icon width and height in pixels.'),

                                    TextInput::make('options.iconSpacing')
                                        ->label('Icon Spacing (px)')
                                        ->live()
                                        ->numeric()
                                        ->default('10')
                                        ->helperText('Gap between icons in pixels.'),

                                    Select::make('options.iconFlex')
                                        ->label('Icon Layout')
                                        ->live()
                                        ->options([
                                            'flex'         => 'Row (horizontal)',
                                            'flex-column'  => 'Column (vertical)',
                                        ])
                                        ->default('flex'),

                                    Select::make('options.iconPosition')
                                        ->label('Icon Alignment')
                                        ->live()
                                        ->options([
                                            'flex-start' => 'Left',
                                            'center'     => 'Center',
                                            'flex-end'   => 'Right',
                                        ])
                                        ->default('center'),
                                ]),
                                Section::make('Design Settings')->schema(
                                    $this->getTemplatesFormSchema()),
                            ]),
                    ]),
            ]);
    }
}
