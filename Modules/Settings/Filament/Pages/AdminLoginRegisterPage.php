<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminLoginRegisterPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-login';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

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











                Section::make('Login options')
                    ->view('filament-forms::sections.section')
                    ->description('Set your settings for proper login and register functionality.')
                    ->schema([
                        Toggle::make('options.users.disable_login')
                            ->label('Disable login')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString(
                                    'If you disable login, the login form will not be shown on the front-end. <br> <strong>Note:</strong> This will not disable the admin login.'
                                );
                            }),
                    ]),
                Section::make('Facebook Login')
                    ->schema([
                        Toggle::make('options.users.enable_user_fb_registration')
                            ->label('Enable Facebook Login')
                            ->live(),
                        TextInput::make('options.users.fb_app_id')
                            ->label('Facebook App ID')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_fb_registration')),
                        TextInput::make('options.users.fb_app_secret')
                            ->label('Facebook App Secret')
                            ->password()
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_fb_registration')),
                        Placeholder::make('Facebook Login Help')
                            ->label('Facebook Login Help')
                            ->helperText(function () {
                                return new HtmlString(
                                    '1. <a href="https://developers.facebook.com/apps" target="_blank">API access</a><br>' .
                                    '2. In Website with Facebook Login please enter: ' . site_url() . '<br>' .
                                    '3. If asked for callback url - use: ' . url('oauth/callback/facebook') . '<br>'
                                );
                            })
                            ->visible(fn(callable $get) => $get('options.users.enable_user_fb_registration')),

                    ]),

                Section::make('Twitter Login')
                    ->schema([
                        Toggle::make('options.users.enable_user_twitter_registration')
                            ->label('Enable Twitter Login')
                            ->live(),
                        TextInput::make('options.users.twitter_app_id')
                            ->label('Twitter App ID')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_twitter_registration')),
                        TextInput::make('options.users.twitter_app_secret')
                            ->label('Twitter App Secret')
                            ->password()
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_twitter_registration')),
                        Placeholder::make('Twitter Login Help')
                            ->label('Twitter Login Help')
                            ->helperText(function () {
                                return new HtmlString(
                                    '1. <a href="https://dev.twitter.com/apps" target="_blank">Register your application</a><br>' .
                                    '2. In Website enter: ' . site_url() . '<br>' .
                                    '3. In Callback URL enter: ' . url('oauth/callback/twitter') . '<br>'
                                );
                            })
                            ->visible(fn(callable $get) => $get('options.users.enable_user_twitter_registration')),

                    ]),

                Section::make('Google Login')
                    ->schema([
                        Toggle::make('options.users.enable_user_google_registration')
                            ->label('Enable Google Login')
                            ->live(),
                        TextInput::make('options.users.google_app_id')
                            ->label('Google App ID')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_google_registration')),
                        TextInput::make('options.users.google_app_secret')
                            ->label('Google App Secret')
                            ->password()
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_google_registration')),
                        Placeholder::make('Google Login Help')
                            ->label('Google Login Help')
                            ->helperText(function () {
                                return new HtmlString(
                                    '1. <a href="https://code.google.com/apis/console/" target="_blank">Set your API access</a><br>' .
                                    '2. In redirect URI please enter: ' . url('oauth/callback/google') . '<br>'
                                );
                            })
                            ->visible(fn(callable $get) => $get('options.users.enable_user_google_registration')),

                    ]),

                Section::make('GitHub Login')
                    ->schema([
                        Toggle::make('options.users.enable_user_github_registration')
                            ->label('Enable GitHub Login')
                            ->live(),
                        TextInput::make('options.users.github_app_id')
                            ->label('GitHub App ID')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_github_registration')),
                        TextInput::make('options.users.github_app_secret')
                            ->label('GitHub App Secret')
                            ->password()
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_github_registration')),
                        Placeholder::make('GitHub Login Help')
                            ->label('GitHub Login Help')
                            ->helperText(function () {
                                return new HtmlString(
                                    '1. <a href="https://github.com/settings/applications/new" target="_blank">Register your application</a><br>' .
                                    '2. In Main URL enter: ' . site_url() . '<br>' .
                                    '3. In Callback URL enter: ' . url('oauth/callback/github') . '<br>'
                                );
                            })
                            ->visible(fn(callable $get) => $get('options.users.enable_user_github_registration')),
                    ]),

                Section::make('LinkedIn Login')
                    ->schema([
                        Toggle::make('options.users.enable_user_linkedin_registration')
                            ->label('Enable LinkedIn Login')
                            ->live(),
                        TextInput::make('options.users.linkedin_app_id')
                            ->label('LinkedIn App ID')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_linkedin_registration')),
                        TextInput::make('options.users.linkedin_app_secret')
                            ->label('LinkedIn App Secret')
                            ->password()
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_linkedin_registration')),
                        Placeholder::make('LinkedIn Login Help')
                            ->label('LinkedIn Login Help')
                            ->helperText(function () {
                                return new HtmlString(
                                    '1. <a href="https://www.linkedin.com/secure/developer" target="_blank">Register your application</a><br>' .
                                    '2. In Website enter: ' . site_url() . '<br>' .
                                    '3. In Callback URL enter: ' . url('oauth/callback/linkedin') . '<br>'
                                );
                            })
                            ->visible(fn(callable $get) => $get('options.users.enable_user_linkedin_registration')),
                    ]),

                Section::make('Microweber Login')
                    ->schema([
                        Toggle::make('options.users.enable_user_microweber_registration')
                            ->label('Enable Microweber Login')
                            ->live(),
                        TextInput::make('options.users.microweber_app_url')
                            ->label('Server URL')
                            ->default('https://mwlogin.com')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_microweber_registration')),
                        TextInput::make('options.users.microweber_app_id')
                            ->label('Microweber App ID')
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_microweber_registration')),
                        TextInput::make('options.users.microweber_app_secret')
                            ->label('Microweber App Secret')
                            ->password()
                            ->reactive()
                            ->visible(fn(callable $get) => $get('options.users.enable_user_microweber_registration')),
                        Placeholder::make('Microweber Login Help')
                            ->label('Microweber Login Help')
                             ->helperText(function () {
                                return new HtmlString(
                                    '1. <a href="https://mwlogin.com/" target="_blank">Register your Microweber application</a><br>' .
                                    '2. In Website enter: ' . site_url() . '<br>' .
                                    '3. In Callback URL enter: ' . url('oauth/callback/microweber') . '<br>'
                                );
                            })
                             ->visible(fn(callable $get) => $get('options.users.enable_user_microweber_registration')),
                    ])
            ]);
    }

}

