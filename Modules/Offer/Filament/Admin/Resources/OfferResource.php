<?php

namespace Modules\Offer\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Modules\Offer\Models\Offer;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components\Utilities\Get;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $recordTitleAttribute = 'offer_price';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelLabel = 'Offer';
    protected static ?int $navigationSort = 8;


    protected static string $description = 'Configure your shop offers settings';

    protected static bool $isGloballySearchable = true;

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

    public static function getGloballySearchableAttributes(): array
    {
        return ['offer_price', 'product.title'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->product?->title ?? 'Offer #' . $record->id;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->offer_price) {
            $details['Price'] = '$' . number_format((float) $record->offer_price, 2);
        }

        $details['Status'] = $record->is_active ? 'Active' : 'Inactive';

        return $details;
    }

    public function getDescription(): string
    {

        return static::$description;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Offer Details')
                    ->icon('heroicon-m-tag')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'title', function ($query) {
                                return $query->with('media');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->allowHtml(true)
                            ->columnSpan('full')
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('price_id', null))
                            ->optionsLimit(50)
                            ->getOptionLabelFromRecordUsing(fn($record) => view('modules.offer::filament.forms.components.product-option', [
                                'title' => $record->title,
                                'price' => $record->price_display,
                                'image' => $record->image,
                            ])),

                        Select::make('price_id')
                            ->label('Price')
                            ->options(function (Get $get) {
                                $productId = $get('product_id');
                                if (!$productId) {
                                    return [];
                                }
                                $prices = app()->shop_manager->get_product_prices($productId, true);

                                if (!$prices) {
                                    return [];
                                }
                                return collect($prices)->mapWithKeys(function ($price, $key) {
                                    return [$price['id'] => $price['name'] . ' - ' . currency_format($price['value'])];
                                })->toArray();
                            })
                            ->required()
                            ->searchable()
                            ->columnSpan('full'),

                        TextInput::make('offer_price')
                            ->label('Offer Price')
                            ->required()
                            ->numeric()
                            ->columnSpan('full'),

                        DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->columnSpan('full'),

                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true)
                            ->columnSpan('full'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // task-2026-05-22-7fcc22 / AI-908 — replace generic Content module empty-state view
            // (which had no Offers branch, rendering a blank div) with Filament-native methods.
            ->emptyStateHeading('No offers yet')
            ->emptyStateDescription('Create your first offer to discount specific products.')
            ->emptyStateActions([
                \Filament\Actions\CreateAction::make()->label('+ New Offer'),
            ])
            ->columns([


                Tables\Columns\ViewColumn::make('product_id')
                    ->label('Product')
                    ->view('modules.offer::filament.tables.columns.product')
                    ->sortable(),

                Tables\Columns\TextColumn::make('offer_price')
                    ->label('Offer Price')
                    ->money()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
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
            'index' => \Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers::route('/'),
            'create' => \Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\CreateOffer::route('/create'),
            'edit' => \Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
