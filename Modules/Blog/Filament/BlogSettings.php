<?php

namespace Modules\Blog\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BlogSettings extends LiveEditModuleSettings
{
    public string $module = 'blog';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // Content Tab
                        Tabs\Tab::make('Content')
                            ->schema([
                                TextInput::make('options.title')
                                    ->label('Blog Title')
                                    ->helperText('Enter the title for your blog.')
                                    ->live()
                                    ->default('My Blog'),

                                TextInput::make('options.posts_per_page')
                                    ->label('Posts Per Page')
                                    ->helperText('Number of posts to display per page')
                                    ->numeric()
                                    ->live()
                                    ->default(10),

                                Toggle::make('options.show_categories')
                                    ->label('Show Categories')
                                    ->helperText('Display blog post categories')
                                    ->live()
                                    ->default(true),

                                Toggle::make('options.show_tags')
                                    ->label('Show Tags')
                                    ->helperText('Display blog post tags')
                                    ->live()
                                    ->default(true),
                            ]),

                        // Design Tab
                        Tabs\Tab::make('Design')
                            ->schema([
                                // Add template settings
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),

                            ])
                    ])
            ]);
    }


}
