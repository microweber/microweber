<?php

namespace Modules\CookieNotice\Filament\Pages;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class CookieNoticeModuleSettingsAdmin extends AdminSettingsPage
{
    protected static bool $shouldRegisterNavigation = false;
    //protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cookie';
    protected string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static string | \UnitEnum | null $navigationGroup = 'Other';
    protected static ?string $title = 'Cookie Notice';
    protected static string | null $navigationLabel = 'Cookie Notice';
    protected static ?int $navigationSort = 11;

    public array $optionGroups = [
        'cookie_notice'
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('General Settings')
                    ->icon('heroicon-m-shield-check')
                    ->schema([
                        Toggle::make('options.cookie_notice.enable_cookie_notice')
                            ->live()
                            ->label('Enable Cookie Notice')
                            ->helperText('Enable or disable cookie notice globally'),

                        TextInput::make('options.cookie_notice.cookie_policy_url')
                            ->live()
                            ->label('Cookie Policy URL')
                            ->helperText('URL to your cookie policy page')
                            ->default('privacy-policy'),

                        ColorPicker::make('options.cookie_notice.background_color')
                            ->live()
                            ->label('Panel Background Color'),

                        ColorPicker::make('options.cookie_notice.text_color')
                            ->live()
                            ->label('Panel Text Color'),

                        TextInput::make('options.cookie_notice.cookie_notice_title')
                            ->live()
                            ->label('Cookie Notice Title')
                            ->helperText('Title of the cookie notice panel')
                            ->default('Cookie Notice'),

                        TextInput::make('options.cookie_notice.cookie_notice_text')
                            ->live()
                            ->label('Cookie Notice Text')
                            ->helperText('Text content of the cookie notice panel')
                            ->default('This website uses cookies to ensure you get the best experience on our website.'),

                        Select::make('options.cookie_notice.panel_toggle_position')
                            ->live()
                            ->label('Panel Toggle Position')
                            ->options([
                                'left' => 'Left',
                                'center' => 'Center',
                                'right' => 'Right'
                            ])
                            ->default('right'),

                    ]),


            ]);
    }
}
