<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class AdminPrivacyPolicyPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-privacy-policy';

    protected static ?string $title = 'Privacy Policy';

    protected static string $description = 'Configure your privacy policy settings';


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
            ]);
    }
}
