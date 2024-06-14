<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class AdminLoginRegisterPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-login-register';

    protected static ?string $title = 'Login & Register';

    protected static string $description = 'Configure your login and registration settings';

    public array $optionGroups = [
        'users'
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Register options')
                    ->view('filament-forms::sections.section')
                    ->description('Set your settings for proper login and register functionality.')
                    ->schema([

                        Toggle::make('options.users.enable_user_registration')
                            ->label('Enable user registration')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2"> Do you allow users to register on your website? If you choose "yes", they will do that with their email.</small>');
                            }),

                        Toggle::make('options.users.registration_approval_required')
                            ->label('Registration email verification')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">Ask users for email verification confirmation after their registration. </small>');
                            }),

                    ]),
            ]);
    }

}

