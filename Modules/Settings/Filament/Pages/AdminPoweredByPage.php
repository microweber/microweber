<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminPoweredByPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Powered By';
    protected static string $description = 'Configure powered by settings';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Powered By Settings')
                    ->description('Configure the powered by text and visibility')
                    ->schema([
                        Toggle::make('options.website.powered_by_link')
                            ->label('Show Powered By Link')
                            ->helperText(new HtmlString('Display "Powered by" link in the footer of your website.'))
                            ->live(),

                        TextInput::make('options.website.powered_by_text')
                            ->label('Powered By Text')
                            ->helperText(new HtmlString('The text to display in the powered by link.'))
                            ->placeholder('Powered by Microweber')
                            ->live()
                            ->visible(fn ($get) => $get('options.website.powered_by_link')),

                        TextInput::make('options.website.powered_by_link_url')
                            ->label('Powered By Link URL')
                            ->helperText(new HtmlString('The URL that the powered by link points to.'))
                            ->placeholder('https://microweber.org')
                            ->url()
                            ->live()
                            ->visible(fn ($get) => $get('options.website.powered_by_link')),

                        Toggle::make('options.website.powered_by_link_target_blank')
                            ->label('Open Link in New Tab')
                            ->helperText(new HtmlString('Open the powered by link in a new browser tab.'))
                            ->live()
                            ->visible(fn ($get) => $get('options.website.powered_by_link')),
                    ])
            ]);
    }
}
