<?php

namespace Modules\Blog\Filament;

use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Product\Filament\Admin\Resources\ProductResource;

class BlogSettings extends LiveEditModuleSettings
{
    public string $module = 'blog';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->schema([
                        // Content Tab
                        Tabs\Tab::make('Content')
                            ->schema([



                                Actions::make([

                                    Action::make('Edit posts')
                                        ->openUrlInNewTab()
                                        ->label('Edit posts')
                                        ->icon('heroicon-o-pencil-square')
                                        ->url(PostResource::getUrl(), shouldOpenInNewTab: true),

                                ]),




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
                                Section::make('Design Settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),

                            ])
                    ])
            ]);
    }


}
