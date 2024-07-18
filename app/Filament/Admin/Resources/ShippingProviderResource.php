<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShippingProviderResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Product\Models\Product;

class ShippingProviderResource extends Resource
{
    protected static ?string $model = \MicroweberPackages\Shipping\Models\ShippingProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {


        return $form
            ->schema([

                //name
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Name')
                    ->required()
                    ->columnSpan('full'),

                //type dropdown

                Forms\Components\Select::make('provider')
                    ->label('Provider')
                    ->live()
                    //->reactive()
//                    ->afterStateUpdated( function (Forms\Components\Select $component,Forms\Set $set, ?string $state) {
//                        $set('provider',$state);
////                        return ($component->getContainer()
////                            ->getComponent('shippingProviderSettings')
////                            ->getChildComponentContainer()
////                            ->fill());
//                    })

                    ->placeholder('Select Provider')
                    ->options([
                        'flat_rate' => 'Flat Rate',
                        'per_item' => 'Per Item',
                        'free_shipping' => 'Free Shipping',
                        'local_pickup' => 'Local Pickup',
                        'local_delivery' => 'Local Delivery',
                        'weight_based_shipping' => 'Weight Based Shipping',
                        'price_based_shipping' => 'Price Based Shipping',
                        'distance_based_shipping' => 'Distance Based Shipping',
                        'custom' => 'Custom',
                    ])
                    ->required()

                    ->columnSpan('full'),

                Forms\Components\ViewField::make('provider-settings')
                    ->key('shippingProviderSettings')
                    ->live()
                    ->reactive()
//                    ->afterStateUpdated(function (Model $record) {
//                        dd($record);
//                        $field->viewData([
//                            'form' => $field->getForm(),
//                        ]);
//
//                    })

                    ->view('shipping::filament.admin.shipping-provider-settings')
                    ->viewData([
                        'form' => $form,
                    ])
                    ->columnSpan('full'),


                Forms\Components\Toggle::make('is_active')
                    ->default(0)
                    ->label('Is Active')
                    ->columnSpan('full')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('id')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Is Active')
                    ->sortable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function ($record): ?string {


                        $product = Product::find(9);

                        if (!$product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);

                    }, shouldOpenInNewTab: true)
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
            'index' => Pages\ListShippingProviders::route('/'),
            'create' => Pages\CreateShippingProvider::route('/create'),
            'edit' => Pages\EditShippingProvider::route('/{record}/edit'),
        ];
    }
}
