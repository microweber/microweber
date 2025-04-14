<?php

namespace Modules\Login\Filament\Pages\Admin;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminLoginSettingsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-login';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Login Settings';

    protected static string $description = 'Configure your login';

    public array $optionGroups = [
        'users'
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Login options')
                    ->view('filament-forms::sections.section')
                    ->description('Set your settings for proper login and register functionality.')
                    ->schema([

                        Toggle::make('options.users.disable_login')
                            ->label(' Disable login')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString(
                                    'If you disable login, the login form will not be shown on the front-end. <br> <strong>Note:</strong> This will not disable the admin login.'
                                );
                            }),


                    ]),
            ]);
    }

}

