<?php

namespace Modules\Tag\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Page\Models\Page;

class TagsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'tags';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tags settings')
                    ->tabs([
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
                                    ->helperText('Select the page to show tags from'),

                                Toggle::make('options.show_tag_counts')
                                    ->live()
                                    ->label('Show Tag Counts')
                                    ->default(true)
                                    ->helperText('Show the number of items tagged with each tag'),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                                Section::make('Style Settings')
                                    ->schema([
                                        ColorPicker::make('options.tag_color')
                                            ->label('Tag Color')
                                            ->live(),

                                        ColorPicker::make('options.tag_hover_color')
                                            ->label('Tag Hover Color')
                                            ->live(),

                                        Select::make('options.tag_size')
                                            ->label('Tag Size')
                                            ->live()
                                            ->options([
                                                'small' => 'Small',
                                                'medium' => 'Medium',
                                                'large' => 'Large'
                                            ])
                                            ->default('medium'),
                                    ]),

                                // Add template settings
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),
                            ]),
                    ]),
            ]);
    }
}
