<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class AdminSeoPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static string $view = 'filament.admin.pages.settings-seo';

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
                            ->helperText(function() {
                                return new HtmlString('<small>If you have a Google Tag Manager account, you can verify ownership of a site using your Google Tag Manager container snippet code. To verify ownership using Google Tag Manager: Choose Google Tag Manager in the verification details page for your site, and follow the instructions shown. <a href="https://support.google.com/webmasters/answer/9008080?hl=en#choose_method&amp;zippy=%2Chtml-tag" target="_blank">Read the article here.</a></small>');
                            })
                            ->placeholder('Enter your website name'),

                        Toggle::make('options.website.toggle')
                            ->label('toggle')
                            ->live()
                            ->helperText('moeto toggleche'),


                        TextInput::make('options.website.qko')
                            ->label('pokazwa se pri toggle')
                            ->hidden(function(Get $get) {
                                return $get('options.website.toggle') !== '1';
                            })
                            ->live()
                            ->helperText('pokazwa se pri toggle'),

                    ]),



            ]);
    }
}
