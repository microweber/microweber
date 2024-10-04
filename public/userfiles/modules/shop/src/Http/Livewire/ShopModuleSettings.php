<?php

namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use Modules\Tag\Model\Tag;

class ShopModuleSettings extends LiveEditModuleSettings implements HasTable
{
    use InteractsWithTable;

    public string $module = 'shop';

    protected static string $view = 'microweber-module-shop::admin.filament.shop-settings';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                ImageUrlColumn::make('media_url')
                    ->imageUrl(function (Product $product) {
                        return $product->mediaUrl();
                    }),

                TextColumn::make('title')
                    ->searchable()
                    ->columnSpanFull()
                    ->weight(FontWeight::Bold),


                TextColumn::make('price_display')
                    ->columnSpanFull(),

                SelectColumn::make('is_active')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ]),


                TextColumn::make('created_at')
                    ->searchable()
                    ->columnSpanFull(),

            ])
            ->paginationPageOptions([
                1,
                3,
                5
            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ])
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('options.from_source')
                    ->options(function () {
                        return Page::query()->pluck('title', 'id');
                    })
                    ->searchable()
                    ->label('From source')
                    ->live(),

                Select::make('options.category_filter')
                ->options([
                    '' => '-- All',
                    'related' => '-- Related',
                    'sub_pages' => '-- Sub Pages',
                    'current_category' => '-- Current Category',
                ]),

                TagsInput::make('options.filter_tags')
                    ->label('Filter tags')
                    ->suggestions(function() {
                        return Tag::query()->pluck('name');
                    })
                    ->live(),

                Radio::make('options.data-show')
                    ->label('Display on post')
                    ->options([
                        'default' => 'Default information from skin',
                        'custom' => 'Custom information',
                    ])
                    ->live()
                    ->label('Display on post')
                    ->default('1'),


                Group::make([
                    Checkbox::make('options.show-thumbnail')
                        ->label('Show Thumbnail'),
                    Checkbox::make('options.show-title')
                        ->label('Show Title'),
                    Checkbox::make('options.show-description')
                        ->label('Show Description'),
                    Checkbox::make('options.show-read-more')
                        ->label('Show Read More'),
                    Checkbox::make('options.show-created-at')
                        ->label('Show Created At'),
                ])->hidden(fn(Get $get) => $get('options.data-show') !== 'custom'),


                Split::make([
                    TextInput::make('options.post-per-page')
                        ->numeric()
                        ->label('Post per page'),
                    Select::make('options.order-boy')
                        ->options([
                            'position+asc' => 'Position (ASC)',
                            'position+desc' => 'Position (DESC)',
                            'created_at+asc' => 'Date (ASC)',
                            'created_at+desc' => 'Date (DESC)',
                            'title+asc' => 'Title (ASC)',
                            'title+desc' => 'Title (DESC)',
                        ])
                        ->label('Order by')
                ])->columns(2)

            ]);
    }
}
