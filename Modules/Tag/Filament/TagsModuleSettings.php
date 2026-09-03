<?php

namespace Modules\Tag\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Page\Models\Page;

class TagsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'tags';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Tags settings')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Select::make('options.data-root-page-id')
                                    ->label('Show Tags from')
                                    ->live()
                                    ->options(function () {
                                        $options = [];
                                        $options[0] = 'Default';

                                        $pages = Page::where('is_active', 1)->get();
                                        if ($pages) {
                                            foreach ($pages as $page) {
                                                $options[$page['id']] = $page['title'];
                                            }
                                        }

                                        return $options;
                                    })
                                    ->default(fn () => $this->getOption('data-root-page-id', 0))
                                    ->helperText('Select the page to show tags from'),

                                Toggle::make('options.show_tag_counts')
                                    ->live()
                                    ->label('Show Tag Counts')
                                    ->default(fn () => filter_var($this->getOption('show_tag_counts', true), FILTER_VALIDATE_BOOLEAN))
                                    ->helperText('Show the number of items tagged with each tag'),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                                Section::make('Style Settings')
                                    ->schema([
                                        ColorPicker::make('options.tag_color')
                                            ->label('Tag Color')
                                            ->live()
                                            ->default(fn () => $this->getOption('tag_color')),

                                        ColorPicker::make('options.tag_hover_color')
                                            ->label('Tag Hover Color')
                                            ->live()
                                            ->default(fn () => $this->getOption('tag_hover_color')),

                                        Select::make('options.tag_size')
                                            ->label('Tag Size')
                                            ->live()
                                            ->options([
                                                'small' => 'Small',
                                                'medium' => 'Medium',
                                                'large' => 'Large'
                                            ])
                                            ->default(fn () => $this->getOption('tag_size', 'medium')),
                                    ]),

                                // Add template settings
                                Section::make('Design Settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),
                            ]),
                    ]),
            ]);
    }
}
