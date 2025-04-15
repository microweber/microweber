<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use Modules\GoogleAnalytics\Filament\Pages\AdminGoogleAnalyticsSettingsPage;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;

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

        $googleAnalyticsExists = false;
        if (route_exists('filament.admin.pages.settings.google-analytics')) {
            $googleAnalyticsExists = true;
        }


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

                        Actions::make([

                            Actions\Action::make('Google Analytics')
                                ->label('Google Analytics')
                                ->icon('heroicon-m-cog')
                                ->color('gray')
                                ->tooltip('Google Analytics server side settings')
                                ->url(route('filament.admin.pages.settings.google-analytics'))


                        ])->visible($googleAnalyticsExists),

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

                        ])->hidden(function (Get $get) {

                            if ($get('options.website.other-search-engines-enabled')) {
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

                    ]),
            ]);
    }
}
