<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminAdvancedPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-advanced';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static string $description = 'Configure your advanced settings';

    protected static ?string $title = 'Advanced';



    public function getView(): string
    {
        return static::$view;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Custom tags')
                    ->view('filament-forms::sections.section')
                    ->description('Allows you to insert custom code in the website header and footer. For e.g. Live chat, Google Ads and others.')
                    ->schema([

                       Textarea::make('options.website.website_head')
                            ->label('Custom head tags')
                            ->live()
                            ->rows(5)
                            ->cols(5)
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">Advanced functionality. You can put custom html in the site head-tags. Please put only valid meta tags or you can break your site.</small>');
                            }),


                        Textarea::make('options.website.website_footer')
                            ->label('Custom footer tags')
                            ->live()
                            ->rows(5)
                            ->cols(5)
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">Advanced functionality. You can put custom html in the site footer-tags. Please put only valid meta tags or you can break your site.</small>');
                            }),

                        Textarea::make('options.website.robots_txt')
                            ->label('Robots.txt content')
                            ->live()
                            ->rows(5)
                            ->cols(5)
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">The robots. txt file, also known as the robots exclusion protocol or standard, is a text file that tells web robots (most often search engines) which pages on your site to crawl. It also tells web robots which pages not to crawl.</small>');
                            }),

                        Textarea::make('options.website.ads_txt')
                            ->label('Ads.txt content')
                            ->live()
                            ->rows(5)
                            ->cols(5),

                    ]),



                  Section::make('Other settings')
                ->description('Other settings for your website.')
                      ->view('filament-forms::sections.section')
                ->schema([

                    Section::make('Internal Settings')
                        ->description('Internal settings for developers')
                        ->schema([
                            Actions::make([
                                Action::make('Internal Settings')
                                    ->fillForm(function () {
                                        return [
                                            'microweber'=>config('microweber')
                                        ];
                                    })
                                    ->form([
                                        Toggle::make('microweber.compile_assets')
                                            ->label('Compile api.js')
                                            ->inline(),
                                        Toggle::make('microweber.force_https')
                                            ->label('Force HTTPS')->inline(),
                                        Select::make('microweber.update_channel')
                                            ->label('Update Channel')
                                            ->options([
                                                'stable' => 'Stable',
                                                'beta' => 'Beta',
                                                'dev' => 'Dev',
                                                'disabled' => 'Disabled',
                                            ]),
                                        Toggle::make('microweber.developer_mode')->inline()
                                    ])->action(function ($data) {
                                        // Handle the action...

                                        // Save the settings

                                        // Return a response...

                                        Notification::make()
                                            ->title('Settings saved')
                                            ->message('The settings have been saved successfully.')
                                            ->success()
                                            ->send();
                                    }),
                            ])

                        ]),

                ]),


            ]);
    }

}
