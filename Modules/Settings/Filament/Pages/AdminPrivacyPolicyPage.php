<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminPrivacyPolicyPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-privacy';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Privacy Policy';

    protected static string $description = 'Configure your privacy policy settings';

    public array $optionGroups = [
        'users',
        'module-settings-group-website-group-settings-group-privacy',
        'contact_form_default',
        'newsletter'

    ];


    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Privacy policy settings')
                    ->view('filament-forms::sections.section')
                    ->description('A Privacy Policy is a legal agreement that explains what kinds of personal information you gather from website visitors, how you use this information, and how you keep it safe. Examples of personal information might include: Names. Dates of birth.
                                    The General Data Protection Regulation (EU) 2016/679 (GDPR) is a regulation in EU law on data protection and privacy in the European Union (EU) and the European Economic Area (EEA).')
                    ->schema([

                        Toggle::make('options.users.require_terms')
                            ->label('Users must agree to the Terms and Conditions')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">Should your users agree to the terms of use of the website</small>');
                            }),

                        TextInput::make('options.users.terms_label')
                            ->label('Terms and conditions text')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">The text will appear to the user</small>');
                            })
                            ->placeholder('I agree with the Terms and Conditions'),

                        TextInput::make('options.users.terms_url')
                            ->label('URL of terms and conditions')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>');
                            })
                            ->placeholder('https://demo.microweber.org/v2/terms'),




                ]),

                Section::make('Contact form settings')
                    ->view('filament-forms::sections.section')
                    ->description('Make settings for your contact form (there may be more than one) related to the conditions for sending data and using the website.')
                    ->schema([

                        Toggle::make('options.module-settings-group-website-group-settings-group-privacy.require_terms')
                            ->label('Users must agree to the terms and conditions')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">If the user does not agree to the terms, he will not be able to use the contact form</small>');
                            }),

                        Placeholder::make('documentation')
                            ->label('Saving data and emails')
                            ->content(new HtmlString('Will you save the information from the emails in your database on the website?'))
                            ->live(),

                        Checkbox::make('options.contact_form_default.skip_saving_emails')
                            ->label('Skip saving emails in my website database.')
                            ->live()


                ]),

                Section::make('Newsletter settings')
                    ->view('filament-forms::sections.section')
                    ->description('Make settings for your contact form (there may be more than one) related to the conditions for sending data and using the website.')
                    ->schema([

                        Toggle::make('options.newsletter.newsletter-settings')
                            ->label('Want to view and edit the text and the page?')
                            ->live(),

                        Section::make([
                            TextInput::make('options.newsletter.terms_label')
                                ->label('Terms and conditions text')
                                ->live()
                                ->helperText(function () {
                                    return new HtmlString('<small class="text-muted d-block mb-2">The text will appear to the user</small>');
                                })
                                ->placeholder('I agree with the Terms and Conditions'),

                            TextInput::make('options.newsletter.terms_url')
                                ->label('URL of terms and conditions')
                                ->live()
                                ->helperText(function () {
                                    return new HtmlString('<small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>');
                                })
                                ->placeholder('https://demo.microweber.org/v2/terms'),


                            ]) ->hidden(function (Get $get) {

                                if ($get('options.newsletter.newsletter-settings')) {
                                    return false;
                                }
                                return true;
                            }),






                ]),
            ]);
    }
}
