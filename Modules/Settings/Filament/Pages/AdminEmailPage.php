<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminEmailPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static string $description = 'Configure your email settings';

    protected static ?string $title = 'Email';

    protected static string | \UnitEnum | null $navigationGroup = 'Email Settings';


    public array $optionGroups = [
        'email'
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('System e-mail website settings')
                    ->icon('heroicon-m-envelope')
                    ->view('mw-filament::sections.section')
                    ->description('Deliver messages related with new registration, password resets and others system functionalities.')
                    ->schema([


                        TextInput::make('options.email.email_from')
                            ->label('From e-mail address')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">The website will send emails on behalf of this address</small>');
                            })
                            ->placeholder('e.g. noreply@yourwebsite.com'),

                        TextInput::make('options.email.email_from_name')
                            ->label('From name')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">The website will use this name for the emails</small>');
                            })
                            ->placeholder('e.g. Your Website Name'),

                    ]),

                Section::make('General e-mail provider settings')
                    ->icon('heroicon-m-server-stack')
                    ->view('mw-filament::sections.section')
                    ->description('Set your settings for proper login and register functionality.')
                    ->schema([


                    // task-2026-05-23-20cfb4 / AI-1054 — default to 'php' so the field
                    // starts with a meaningful selection instead of a null placeholder.
                    Select::make('options.email.email_transport')
                        ->label('Email Transport')
                        ->live()
                        ->default('php')
                        ->options([
                            'php' => 'PHP mail function',
                            'gmail' => 'Gmail',
                            'smtp' => 'Smtp',
                            'cpanel' => 'Cpanel',
                            'plesk' => 'Plesk',
                            'config' => 'Config'
                        ]),

                    // AI-1054: Section heading 'Email Transport' hidden until a transport is
                    // selected to avoid it rendering as an orphaned h3 before any choice.
                    Section::make('Email Transport')
                        ->hidden(fn (Get $get) => !$get('options.email.email_transport'))
                        ->schema(
                        [
                            Group::make([

                                TextInput::make('options.email.smtp_username')
                                    ->label('Gmail Username')
                                    ->live()
                                    ->placeholder('e.g. user@email.com'),

                                TextInput::make('options.email.smtp_password')
                                    ->label('Gmail Password')
                                    ->live()
                                    ->password()
                                    ->placeholder('your password here'),
                            ]) ->hidden(function (Get $get) {

                                if ($get('options.email.email_transport') == 'gmail') {
                                    return false;
                                }
                                return true;
                            }),

                            Group::make([

                                TextInput::make('options.email.smtp_username')
                                    ->label('SMTP Username')
                                    ->live()
                                    ->placeholder('e.g. user@email.com'),

                                TextInput::make('options.email.smtp_password')
                                    ->label('Gmail Password')
                                    ->live()
                                    ->password()
                                    ->placeholder('your password here'),

                                TextInput::make('options.email.smtp_host')
                                    ->label('SMTP Host')
                                    ->live()
                                    ->placeholder('e.g. smtp.gmail.com'),

                                TextInput::make('options.email.smtp_port')
                                    ->label('SMTP Port')
                                    ->live()
                                    ->placeholder('e.g. 587'),

                                Select::make('options.email.smtp_auth')
                                    ->label('Enable SMTP authentication')
                                    ->live()
                                    ->options([
                                        '' => 'None',
                                        'ssl' => 'SSL',
                                        'tls' => 'TLS',
                                    ]),

                                Select::make('options.email.smtp_secure')
                                    ->label('SMTP Secure')
                                    ->live()
                                    ->options([
                                        '' => 'None',
                                        'ssl' => 'SSL',
                                        'tls' => 'TLS',
                                    ]),



                            ]) ->hidden(function (Get $get) {

                                if ($get('options.email.email_transport') == 'smtp') {
                                    return false;
                                }
                                return true;
                            }),

                            Group::make([

                                TextInput::make('options.email.smtp_username')
                                    ->label('Cpanel Username')
                                    ->live()
                                    ->placeholder('e.g. user@email.com'),

                                TextInput::make('options.email.smtp_password')
                                    ->label('Cpanel Password')
                                    ->live()
                                    ->password()
                                    ->placeholder('your password here'),

                                TextInput::make('options.email.smtp_host')
                                    ->label('Cpanel Host')
                                    ->live()
                                    ->placeholder('e.g. smtp.gmail.com'),

                            ]) ->hidden(function (Get $get) {

                                if ($get('options.email.email_transport') == 'cpanel') {
                                    return false;
                                }
                                return true;
                            }),

                             Group::make([

                                TextInput::make('options.email.smtp_username')
                                    ->label('Plesk Username')
                                    ->live()
                                    ->placeholder('e.g. user@email.com'),

                                TextInput::make('options.email.smtp_password')
                                    ->label('Plesk Password')
                                    ->live()
                                    ->password()
                                    ->placeholder('your password here'),

                                TextInput::make('options.email.smtp_host')
                                    ->label('Plesk Host')
                                    ->live()
                                    ->placeholder('e.g. smtp.gmail.com'),

                            ]) ->hidden(function (Get $get) {

                                if ($get('options.email.email_transport') == 'plesk') {
                                    return false;
                                }
                                return true;
                            }),

                        ]
                    )


                ]),
            ]);
    }

}

