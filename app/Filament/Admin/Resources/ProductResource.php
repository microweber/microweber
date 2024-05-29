<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Marketplace\Models\MarketplaceItem;
use MicroweberPackages\Product\Models\Product;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'mw-shop';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Tabs::make('Tabs')
                    ->tabs([

                        Forms\Components\Tabs\Tab::make('Details')
                            ->schema([

                                Forms\Components\Group::make()
                                    ->schema([

                                        Forms\Components\Section::make('General Information')
                                            ->heading(false)
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->columnSpanFull()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                                        if ($operation !== 'create') {
                                                            return;
                                                        }

                                                        $set('url', Str::slug($state));
                                                    }),

                                                Forms\Components\TextInput::make('url')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->columnSpanFull()
                                                    ->unique(Product::class, 'url', ignoreRecord: true),

                                                Forms\Components\MarkdownEditor::make('description')
                                                    ->columnSpan('full'),
                                            ])
                                            ->columnSpanFull()
                                            ->columns(2),


                                        Forms\Components\Section::make('Pricing')
                                            ->schema([


                                            ])->columnSpanFull(),


                                        Forms\Components\Section::make('Inventory')
                                            ->schema([


                                            ])->columnSpanFull(),

                                        Forms\Components\Section::make('Shipping')
                                            ->schema([


                                            ])->columnSpanFull(),

                                    ])->columnSpan(['lg' => 2]),


                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make('Visible')
                                            ->schema([
                                                Forms\Components\ToggleButtons::make('is_published')
                                                    ->label(false)
                                                    ->options([
                                                        1 => 'Published',
                                                        0 => 'Unpublished',
                                                    ])
                                                    ->default(true),

                                            ]),
                                        Forms\Components\Section::make('Category')
                                            ->schema([

                                            ]),

                                        Forms\Components\Section::make('Tags')
                                            ->schema([
                                                Forms\Components\TagsInput::make('tags')
                                                    ->label(false)
                                                    ->helperText('Separate using commas or Enter key.')
                                                    ->placeholder('Add a tag'),
                                            ]),

                                    ])->columnSpan(['lg' => 1]),



                            ])->columns(3),


                Forms\Components\Tabs\Tab::make('Custom Fields')
                    ->schema([

                    ]),
                Forms\Components\Tabs\Tab::make('SEO')
                    ->schema([

                    ]),
                Forms\Components\Tabs\Tab::make('Advanced')
                    ->schema([

                    ]),
                    ])->columnSpanFull(),

            ]);
    }


    public static function getListTableColumns(): array
    {

        return [
            ImageUrlColumn::make('media_url')
                ->imageUrl(function (Product $product) {
                    return $product->mediaUrl();
                }),

            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->columnSpanFull()
                ->weight(FontWeight::Bold),

            Tables\Columns\TextColumn::make('price_display')
                ->searchable()
                ->columnSpanFull(),

            Tables\Columns\SelectColumn::make('is_active')
                ->options([
                    1 => 'Published',
                    0 => 'Unpublished',
                ]),

        ];
    }

    public static function getGridTableColumns(): array
    {
        return [
            Tables\Columns\Layout\Split::make([

                ImageUrlColumn::make('media_url')
                    ->imageUrl(function (Product $product) {
                        return $product->mediaUrl();
                    }),


                Tables\Columns\Layout\Stack::make([

                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->columnSpanFull()
                        ->weight(FontWeight::Bold),


                ]),

                Tables\Columns\TextColumn::make('price_display')
                    ->searchable()
                    ->columnSpanFull(),

                Tables\Columns\SelectColumn::make('is_active')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ]),


                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->columnSpanFull(),

            ])
        ];
    }

    public static function table(Table $table): Table
    {

        $livewire = $table->getLivewire();

        return $table
            ->deferLoading()
            ->reorderable('position')
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->contentGrid(
                fn() => $livewire->isListLayout()
                    ? null
                    : [
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]
            )
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
