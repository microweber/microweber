<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class AdminAdvancedPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static string $description = 'Configure your advanced settings';

    protected static ?string $title = 'Advanced';

    protected static string | \UnitEnum | null $navigationGroup = 'Other';

    public function getView(): string
    {
        return static::$view;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Custom tags')
                    ->view('mw-filament::sections.section')
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
                    ->view('mw-filament::sections.section')
                    ->schema([

                        Section::make('Internal Settings')
                            ->description('Internal settings for developers')
                            ->schema([
                                Actions::make([
                                    Action::make('Internal Settings')
                                        ->fillForm(function () {
                                            return [
                                                'microweber' => config('microweber')
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









                Section::make('Web manifest App icons')
                    ->description('Web manifest and app icons settings')
                    ->view('mw-filament::sections.section')
                    ->schema([



                        Section::make('Web manifest App Icons')
                            ->description('Configure icons for your Progressive Web App (PWA)')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        MwFileUpload::make('options.website.manifest_app_icon')
                                            ->label('App Icon (144x144)')
                                            ->helperText(new HtmlString('Select a PNG image for your website app icon.<br>Must be exactly 144x144 pixels.'))
                                            ->live()
                                            ->required(),

                                        MwFileUpload::make('options.website.maskable_icon')
                                            ->label('Maskable Icon (512x512)')
                                            ->helperText(new HtmlString('Select a PNG image for your website maskable icon.<br>Must be exactly 512x512 pixels.'))
                                            ->live()
                                            ->required(),
                                    ])
                            ])








                    ]),





                Section::make('Trust Proxies Settings')
                    ->description('Configure trusted proxies for your website')
                    ->view('mw-filament::sections.section')
                    ->schema([


                        Section::make('Trust Proxies Settings')
                            ->description('Configure trusted proxies for your website')
                            ->schema([
                                Toggle::make('options.website.trust_proxies_enabled')
                                    ->label('Enable Trusted Proxies')
                                    ->helperText(new HtmlString('Enable this if your website is behind a proxy or load balancer.'))
                                    ->live(),

                                Textarea::make('options.website.trust_proxies')
                                    ->label('Trusted Proxies')
                                    ->helperText(new HtmlString('Enter IP addresses of trusted proxies, one per line.<br>You can use CIDR notation (e.g., 192.168.1.0/24) or specific IPs (e.g., 192.168.1.1).'))
                                    ->placeholder("192.168.1.0/24\n10.0.0.0/8")
                                    ->rows(5)
                                    ->visible(fn ($get) => $get('options.website.trust_proxies_enabled'))
                                    ->live(),

                                Toggle::make('options.website.trust_all_proxies')
                                    ->label('Trust All Proxies')
                                    ->helperText(new HtmlString('WARNING: Only enable this if you are sure about your infrastructure security.<br>This will trust all proxies (*).'))
                                    ->visible(fn ($get) => $get('options.website.trust_proxies_enabled'))
                                    ->live(),

                                Toggle::make('options.website.trust_cloudflare')
                                    ->label('Trust Cloudflare')
                                    ->helperText(new HtmlString('Enable this if you are using Cloudflare as your proxy.<br>This will automatically trust Cloudflare\'s IP ranges.'))
                                    ->visible(fn ($get) => $get('options.website.trust_proxies_enabled'))
                                    ->live(),
                            ])





                    ]),



            ]);
    }

}
