<?php

namespace Modules\Register\Filament\Pages\Admin;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminRegisterSettingsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-login';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Register settings';

    protected static string $description = 'Configure your registration settings';

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

