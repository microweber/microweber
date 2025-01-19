<?php

namespace Modules\CookieNotice\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class CookieNoticeModuleSettingsAdmin extends AdminSettingsPage
{
    protected static bool $shouldRegisterNavigation = true;
    //protected static ?string $navigationIcon = 'heroicon-o-cookie';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Cookie Notice Settings';
    protected static ?string $navigationLabel = 'Cookie Notice';
    protected static ?int $navigationSort = 11;

    public array $optionGroups = [
        'cookie_notice'
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Settings')
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
