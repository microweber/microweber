<?php

namespace Modules\Shop\Filament;

use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Modules\Multilanguage\Filament\Pages\MultilanguageSettingsAdmin;
use Modules\Page\Models\Page;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Product\Filament\Admin\Resources\ProductResource;

class ShopModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'shop';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Shop Settings')
                    ->schema([
                        Tabs\Tab::make('Items list')
                            ->schema(
                                [

                                    Actions::make([

                                        Action::make('Edit products')
                                            ->openUrlInNewTab()
                                            ->label('Edit products')
                                            ->icon('heroicon-o-pencil-square')
                                            ->url(ProductResource::getUrl(), shouldOpenInNewTab: true),

                                    ]),

                                    Select::make('options.content_from_id')
                                        ->label('Content From')
                                        ->options(Page::query()
                                            ->where('is_shop', 1)
                                            ->whereNotNull('title')
                                            ->pluck('title', 'id'))
                                        ->searchable()
                                        ->live()
                                        ->default(fn () => $this->getOption('content_from_id', null))
                                        ->placeholder('Select a shop page'),

                                    Select::make('options.default_sort')
                                        ->label('Default Sort')
                                        ->options([
                                            'created_by_asc' => 'Created (Ascending)',
                                            'created_by_desc' => 'Created (Descending)',
                                            'price_asc' => 'Price (Low to High)',
                                            'price_desc' => 'Price (High to Low)',
                                        ])
                                        ->live()
                                        ->default(fn () => $this->getOption('default_sort', 'created_by_desc'))
                                        ->placeholder('Select sort order'),

                                    TextInput::make('options.default_limit')
                                        ->label('Items Per Page')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(100)
                                        ->default(fn () => $this->getOption('default_limit', 10))
                                        ->live(),

                                    // task-2026-05-21-a4832f / AI-872 Slice 1 — Shop: Columns + toggles
                                    ToggleButtons::make('options.columns')
                                        ->label('Columns')
                                        ->live()
                                        ->inline()
                                        ->options([
                                            '2' => '2',
                                            '3' => '3',
                                            '4' => '4',
                                        ])
                                        ->default(fn () => $this->getOption('columns', '3')),

                                    Toggle::make('options.show_price')
                                        ->label('Show Price')
                                        ->live()
                                        ->default(true),

                                    Toggle::make('options.show_add_to_cart')
                                        ->label('Show Add to Cart Button')
                                        ->live()
                                        ->default(true),

//                                  Toggle::make('options.filtering_by_tags')
//                                        ->label('Enable Tag Filtering')
//                                        ->live()
//                                        ->default(true),
//
//                                    Toggle::make('options.filtering_by_categories')
//                                        ->label('Enable Category Filtering')
//                                        ->live()
//                                        ->default(true),
//
//                                    Toggle::make('options.filtering_by_custom_fields')
//                                        ->label('Enable Custom Field Filtering')
//                                        ->live()
//                                        ->default(true),
                                ]
                            ),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
