<?php

namespace Modules\Offer\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Offer\Models\Offer;
use Filament\Forms\Get;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationGroup = 'Shop Settings';

    protected static ?string $modelLabel = 'Offer';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);
            })
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => \Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers::route('/'),
            'create' => \Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\CreateOffer::route('/create'),
            'edit' => \Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
