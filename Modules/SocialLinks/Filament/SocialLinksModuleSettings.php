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
                                    ->default(fn () => filter_var($this->getOption('facebook_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.facebook_url')
                                    ->label('Facebook URL')
                                    ->live()
                                    ->placeholder('Enter Facebook URL')
                                    ->default(fn () => $this->getOption('facebook_url', ''))
                                    ->visible(fn($get) => $get('options.facebook_enabled')),

                                Toggle::make('options.x_enabled')
                                    ->label('Enable X Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('x_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.x_url')
                                    ->label('X URL')
                                    ->live()
                                    ->placeholder('Enter X URL')
                                    ->default(fn () => $this->getOption('x_url', ''))
                                    ->visible(fn($get) => $get('options.x_enabled')),

                                Toggle::make('options.pinterest_enabled')
                                    ->label('Enable Pinterest Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('pinterest_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.pinterest_url')
                                    ->label('Pinterest URL')
                                    ->live()
                                    ->placeholder('Enter Pinterest URL')
                                    ->default(fn () => $this->getOption('pinterest_url', ''))
                                    ->visible(fn($get) => $get('options.pinterest_enabled')),

                                Toggle::make('options.linkedin_enabled')
                                    ->label('Enable LinkedIn Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('linkedin_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->live()
                                    ->placeholder('Enter LinkedIn URL')
                                    ->default(fn () => $this->getOption('linkedin_url', ''))
                                    ->visible(fn($get) => $get('options.linkedin_enabled')),

                                Toggle::make('options.viber_enabled')
                                    ->label('Enable Viber Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('viber_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.viber_url')
                                    ->label('Viber URL')
                                    ->live()
                                    ->placeholder('Enter Viber URL')
                                    ->default(fn () => $this->getOption('viber_url', ''))
                                    ->visible(fn($get) => $get('options.viber_enabled')),

                                Toggle::make('options.whatsapp_enabled')
                                    ->label('Enable WhatsApp Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('whatsapp_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.whatsapp_url')
                                    ->label('WhatsApp URL')
                                    ->live()
                                    ->placeholder('Enter WhatsApp URL')
                                    ->default(fn () => $this->getOption('whatsapp_url', ''))
                                    ->visible(fn($get) => $get('options.whatsapp_enabled')),

                                Toggle::make('options.telegram_enabled')
                                    ->label('Enable Telegram Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('telegram_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.telegram_url')
                                    ->label('Telegram URL')
                                    ->live()
                                    ->placeholder('Enter Telegram URL')
                                    ->default(fn () => $this->getOption('telegram_url', ''))
                                    ->visible(fn($get) => $get('options.telegram_enabled')),

                                Toggle::make('options.youtube_enabled')
                                    ->label('Enable YouTube Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('youtube_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.youtube_url')
                                    ->label('YouTube URL')
                                    ->live()
                                    ->placeholder('Enter YouTube URL')
                                    ->default(fn () => $this->getOption('youtube_url', ''))
                                    ->visible(fn($get) => $get('options.youtube_enabled')),

                                Toggle::make('options.instagram_enabled')
                                    ->label('Enable Instagram Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('instagram_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.instagram_url')
                                    ->label('Instagram URL')
                                    ->live()
                                    ->placeholder('Enter instagram URL')
                                    ->default(fn () => $this->getOption('instagram_url', ''))
                                    ->visible(fn($get) => $get('options.instagram_enabled')),

                                Toggle::make('options.github_enabled')
                                    ->label('Enable GitHub Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('github_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.github_url')
                                    ->label('GitHub URL')
                                    ->live()
                                    ->placeholder('Enter GitHub URL')
                                    ->default(fn () => $this->getOption('github_url', ''))
                                    ->visible(fn($get) => $get('options.github_enabled')),

                                Toggle::make('options.soundcloud_enabled')
                                    ->label('Enable SoundCloud Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('soundcloud_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.soundcloud_url')
                                    ->label('SoundCloud URL')
                                    ->live()
                                    ->placeholder('Enter SoundCloud URL')
                                    ->default(fn () => $this->getOption('soundcloud_url', ''))
                                    ->visible(fn($get) => $get('options.soundcloud_enabled')),

                                Toggle::make('options.discord_enabled')
                                    ->label('Enable Discord Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('discord_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.discord_url')
                                    ->label('Discord URL')
                                    ->live()
                                    ->placeholder('Enter Discord URL')
                                    ->default(fn () => $this->getOption('discord_url', ''))
                                    ->visible(fn($get) => $get('options.discord_enabled')),

                                Toggle::make('options.skype_enabled')
                                    ->label('Enable Skype Sharing')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('skype_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                TextInput::make('options.skype_url')
                                    ->label('Skype URL')
                                    ->live()
                                    ->placeholder('Enter Skype URL')
                                    ->default(fn () => $this->getOption('skype_url', ''))
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
                                        ->default(fn () => $this->getOption('iconSize', '24'))
                                        ->helperText('Icon width and height in pixels.'),

                                    TextInput::make('options.iconSpacing')
                                        ->label('Icon Spacing (px)')
                                        ->live()
                                        ->numeric()
                                        ->default(fn () => $this->getOption('iconSpacing', '10'))
                                        ->helperText('Gap between icons in pixels.'),

                                    Select::make('options.iconFlex')
                                        ->label('Icon Layout')
                                        ->live()
                                        ->options([
                                            'flex'         => 'Row (horizontal)',
                                            'flex-column'  => 'Column (vertical)',
                                        ])
                                        ->default(fn () => $this->getOption('iconFlex', 'flex')),

                                    Select::make('options.iconPosition')
                                        ->label('Icon Alignment')
                                        ->live()
                                        ->options([
                                            'flex-start' => 'Left',
                                            'center'     => 'Center',
                                            'flex-end'   => 'Right',
                                        ])
                                        ->default(fn () => $this->getOption('iconPosition', 'center')),
                                ]),
                                Section::make('Design Settings')->schema(
                                    $this->getTemplatesFormSchema()),
                            ]),
                    ]),
            ]);
    }
}
