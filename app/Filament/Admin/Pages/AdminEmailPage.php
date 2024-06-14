<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class AdminEmailPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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

                Section::make('Register options')
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
                                        'gmail' => 'Gmail',
                                        'smtp' => 'Smtp',
                                        'cpanel' => 'Cpanel',
                                        'plesk' => 'Plesk',
                                        'config' => 'Config'
                                    ]),


                            ]) ->hidden(function (Get $get) {

                                if ($get('options.email.email_transport') == 'smtp') {
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

