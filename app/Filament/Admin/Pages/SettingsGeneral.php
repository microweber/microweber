<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class SettingsGeneral extends SettingsPageDefault
{

    protected static ?string $slug = 'settings/general';

    protected static string $view = 'filament.admin.pages.settings-general';

    protected static ?string $title = 'General';

    protected static string $description = 'Make basic settings for your website.';
    protected static ?string $navigationGroup = 'Website Settings';


    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Seo Settings')
                    ->view('filament-forms::sections.section')
                    ->description(' Fill in the fields for maximum results when finding your website in search engines.')
            ->schema([

                TextInput::make('website_name')
                    ->live()
                    ->label('Website Name')
                    ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its name is one of them.')
                    ->placeholder('Enter your website name'),

                Textarea::make('website_description')
                    ->live()
                    ->label('Website Description')
                    ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its description is one of them.')
                    ->placeholder('Enter your website description'),

                TagsInput::make('website_keywords')
                    ->live()
                    ->label('Website Keywords')
                    ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its keywords are one of them.')
                    ->placeholder('Enter your website keywords'),


            ]),


            ]);
    }
}
