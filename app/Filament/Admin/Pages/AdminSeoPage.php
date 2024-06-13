<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

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

                Section::make('SECTION')
                    ->view('filament-forms::sections.section')
                    ->description('SECTION DESCRIPTOION')
                    ->schema([

                        TextInput::make('options.website.test')
                            ->label('Test Field')
                            ->helperText('This is very important for search engines. Your website will be categorized by many criteria and its name is one of them.')
                            ->placeholder('Enter your website name'),

                    ])

            ]);
    }
}
