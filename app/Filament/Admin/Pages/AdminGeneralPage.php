<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class AdminGeneralPage extends AdminSettingsPage
{

    protected static ?string $slug = 'settings/general';

    protected static string $view = 'filament.admin.pages.settings-general';
    protected static ?string $navigationIcon = 'mw-general';


    protected static ?string $title = 'General';

    protected static string $description = 'Make basic settings for your website.';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Seo Settings')
                    ->view('filament-forms::sections.section')
                    ->description('Fill in the fields for maximum results when finding your website in search engines.')
                    ->schema([

                        TextInput::make('options.website.website_title')
                            ->label('Website Name')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its name is one of them.')
                            ->placeholder('Enter your website name')
                            ->live()
                            ->mwTranslatableOption(),

                        Textarea::make('options.website.website_description')
                            ->label('Website Description')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its description is one of them.')
                            ->placeholder('Enter your website description')
                            ->live()
                            ->mwTranslatableOption(),

                        TextInput::make('options.website.website_keywords')
                            ->label('Website Keywords')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its keywords are one of them.')
                            ->placeholder('Enter your website keywords')
                            ->live()
                            ->mwTranslatableOption(),


                        Select::make('options.website.permalink_structure')
                            ->label('Permalink Structure')
                            ->live()
                            ->options([
                                'post' => 'sample-post',
                                'page_post' => 'page/sample-post',
                                'category_post' => 'sample-category/sample-post',
                                'page_category_post' => 'sample-page/sample-category/sample-post',
                            ])
                            ->placeholder('Select Permalink Structure'),

                    ]),


                Section::make('General Settings')
                    ->view('filament-forms::sections.section')
                    ->description('Set regional settings for your website or online store They will also affect the language you use and the fees for the orders.')
                    ->schema([

                        Select::make('options.website.date_format')
                            ->label('Date Format')
                            ->helperText('Choose a date format for your website')
                            ->live()
                            ->options(function () {
                                $options = [];
                                foreach (app()->format->get_supported_date_formats() as $item) {
                                    $options[$item] = date($item, time()) . '- ('.$item.')';
                                }
                                return $options;
                            }),

                        Select::make('options.website.time_zone')
                            ->label('Time Zone')
                            ->helperText('Set a time zone')
                            ->live()
                            ->options(function () {
                                return timezone_identifiers_list();
                            }),


                        Select::make('options.website.items_per_page')
                            ->label('Posts per page')
                            ->helperText('Select how many posts or products you want to be shown per page?')
                            ->live()
                            ->options(function () {
                                $postsPerPage = [];
                                for ($i = 5; $i < 40; $i += 5) {
                                    $postsPerPage[$i] = $i;
                                }
                                return $postsPerPage;
                            }),

                        Split::make([
                            MwFileUpload::make('options.website.logo')
                                ->label('Website Logo')
                                ->helperText('Select an logo for your website.')
                                ->live(),
                            MwFileUpload::make('options.website.favicon_image')
                                ->label('Website Favicon')
                                ->helperText('Select an icon for your website.')
                                ->live(),
                        ])->columns(2)

                    ]),

                Section::make('Online Shop')
                    ->view('filament-forms::sections.section')
                    ->description('Enable or disable your online shop')
                    ->schema([

                        Toggle::make('options.website.shop_disabled')
                            ->label('Enable Online shop')
                            ->live()
                            ->helperText('Choose the status of your online shop'),

                    ]),

                Section::make('Maintenance mode')
                    ->view('filament-forms::sections.section')
                    ->description('Enable or disable maintenance mode on your site')
                    ->schema([

                        Toggle::make('options.website.maintenance_mode')
                            ->label('Maintenance mode')
                            ->live()
                            ->helperText('Turn on Under construction mode of your site'),

                    ]),

                Section::make('Powered by Microweber')
                    ->view('filament-forms::sections.section')
                    ->description('Control whether or not "Powered by Microweber" links display in the footer of your site and products.')
                    ->schema([

                        Actions::make([
                            Actions\Action::make('edit_powered_by_microweber')
                                ->size('lg')
                                ->label('Click here to edit the branding settings'),
                        ])->columnSpanFull(),

                    ]),


            ]);
    }
}
