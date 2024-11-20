<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminEmailPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-email';

    protected static string $view = 'filament.admin.pages.settings-email';

    protected static string $description = 'Configure your email settings';

    protected static ?string $title = 'Email';

    public array $optionGroups = [
        'email'
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('System e-mail website settings')
                    ->view('filament-forms::sections.section')
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
                    ->view('filament-forms::sections.section')
                    ->description('Set your settings for proper login and register functionality.')
                    ->schema([


                    Select::make('options.email.email_transport')
                        ->label('Email Transport')
                        ->live()
                        ->options([
                            'php' => 'PHP mail function',
                            'gmail' => 'Gmail',
                            'smtp' => 'Smtp',
                            'cpanel' => 'Cpanel',
                            'plesk' => 'Plesk',
                            'config' => 'Config'
                        ]),

                    Section::make('Email Transport') ->schema(
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

