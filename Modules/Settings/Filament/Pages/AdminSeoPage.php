<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminSeoPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-seo';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'SEO';

    protected static string $description = 'Configure your SEO settings';

    protected static ?string $slug = 'settings/seo-page';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('SEO Settings')
                    ->view('filament-forms::sections.section')
                    ->description('Make these settings to get the best results when finding your website.')
                    ->schema([

                        TextInput::make('options.website.google-site-verification-code')
                            ->label('Google site verification code')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">If you have a Google Tag Manager account, you can verify ownership of a site using your Google Tag Manager container snippet code. To verify ownership using Google Tag Manager: Choose Google Tag Manager in the verification details page for your site, and follow the instructions shown. <a href="https://support.google.com/webmasters/answer/9008080?hl=en#choose_method&amp;zippy=%2Chtml-tag" class="text-blue-500" target="_blank">Read the article here.</a></small>');
                            })
                            ->placeholder('Enter your website name'),

                        TextInput::make('options.website.google-analytics-id')
                            ->label('Google Analytics ID')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="text-muted d-block mb-2">Google Analytics property ID is the identifier associated with your account and used by Google Analytics to collect the data. <a class="text-blue-500" href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank">How to find it read here.</a></small>');
                            })
                            ->placeholder('UA- 12345678-9'),


                        Toggle::make('options.website.google-measurement-enabled')
                            ->label('Google Analytics Server Side Tracking')
                            ->live()
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">Enable Google Analytics Server Side Tracking</small>');
                                }),

                                Section::make([
                                    TextInput::make('options.website.google-measurement-api-secret')
                                        ->label('Google Measurement Api Secret')
                                        ->live()
                                        ->helperText(function () {
                                            return new HtmlString('<small class="text-muted d-block mb-2">Google measurement api secret.<a class="text-blue-500" href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank">How to find it read here.</a>
                                                            <a class="text-blue-500" href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/" target="_blank">Protocol reference</a>
                                                            To create a new secret, navigate in the Google Analytics UI to:                <i>Admin &gt; Data Streams &gt; choose your stream &gt; Measurement Protocol &gt; Create</i>
                                                       </small>');
                                        })
                                        ->placeholder('Enter your Google Measurement Api Secret'),

                                    TextInput::make('options.website.google-measurement-id')
                                        ->label('Google Measurement ID')
                                        ->live()
                                        ->helperText(function () {
                                            return new HtmlString('<small class="text-muted d-block mb-2">Google measurement property ID is the identifier associated with your account and used by Google Analytics to collect the data. <a class="text-blue-500" href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank">How to find it read here.</a></small>');
                                        })
                                        ->placeholder('G-12345678'),

                                    Toggle::make('options.website.google-enhanced-conversions-enabled')
                                        ->label('Google Measurement Enhanced Conversion')
                                        ->live()
                                        ->helperText(function () {
                                            return new HtmlString('<small class="mb-2 text-muted">Enable Google Measurement Enhanced Conversion</small>');
                                        }),
                                            Section::make([
                                                TextInput::make('options.website.google-enhanced-conversion-id')
                                                    ->label('Conversion ID')
                                                    ->live()
                                                    ->placeholder('Enter your Google Measurement Enhanced Conversion ID'),

                                                TextInput::make('options.website.google-enhanced-conversion-label')
                                                    ->label('Conversion Label')
                                                    ->live()
                                                    ->placeholder('Enter your Google Measurement Enhanced Conversion Label'),


                                            ]) ->hidden(function (Get $get) {

                                                if ($get('options.website.google-enhanced-conversions-enabled')) {
                                                    return false;
                                                }
                                                return true;
                                            }),

                                    TextInput::make('options.website.facebook-pixel-id')
                                        ->label('Facebook pixel ID')
                                        ->live()
                                        ->helperText(function () {
                                            return new HtmlString('<small class="text-muted d-block mb-2">You can find a tutorials in internet where and how to find the code.</small>');
                                        })
                                        ->placeholder('Enter your Facebook pixel ID'),



                                ]) ->hidden(function (Get $get) {

                                    if ($get('options.website.google-measurement-enabled')) {
                                        return false;
                                    }
                                    return true;
                                }),
                        Toggle::make('options.website.other-search-engines-enabled')
                            ->label('Other search engines')
                            ->live(),

                        Section::make([
                            TextInput::make('options.website.bing-site-verification-code')
                                ->label('Bing')
                                ->live()
                                ->helperText(function () {
                                    return new HtmlString('<small class="text-muted d-block mb-2">You can find a tutorials in internet where and how to find the code.</small>');
                                }),

                            TextInput::make('options.website.alexa-site-verification-code')
                                ->label('Alexa')
                                ->live()
                                ->helperText(function () {
                                    return new HtmlString('<small class="text-muted d-block mb-2">You can find a tutorials in internet where and how to find the code.</small>');
                                }),


                            TextInput::make('options.website.pinterest-site-verification-code')
                                ->label('Pinterest')
                                ->live()
                                ->helperText(function () {
                                    return new HtmlString('<small class="text-muted d-block mb-2">You can find a tutorials in internet where and how to find the code.</small>');
                                }),

                            TextInput::make('options.website.yandex-site-verification-code')
                                ->label('Site verification code for Yandex')
                                ->live()
                                ->helperText(function () {
                                    return new HtmlString('<small class="text-muted d-block mb-2">You can find a tutorials in internet where and how to find the code.</small>');
                                }),

                        ]) ->hidden(function (Get $get) {

                            if ($get('options.website.other-search-engines-enabled')) {
                                return false;
                            }
                            return true;
                        }),

                    ]),
            ]);
    }
}
