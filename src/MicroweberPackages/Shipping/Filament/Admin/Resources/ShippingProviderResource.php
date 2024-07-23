<?php

namespace MicroweberPackages\Shipping\Filament\Admin\Resources;


use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class ShippingProviderResource extends Resource
{
    protected static ?string $model = \MicroweberPackages\Shipping\Models\ShippingProvider::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';


    public static function form(Form $form): Form
    {
        $schema = [

            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->placeholder('Name')
                ->required()
                ->columnSpan('full'),

            Forms\Components\Select::make('provider')
                ->label('Provider')
                ->live()
                ->reactive()
                ->afterStateUpdated(function (Forms\Components\Select $component, Forms\Set $set, ?string $state) {

                    $set('provider', $state);
                })
                ->placeholder('Select Provider')
                ->options([
                    'flat_rate' => 'Flat Rate',
                    'per_item' => 'Per Item',
                    'free_shipping' => 'Free Shipping',
                    'local_delivery' => 'Local Delivery'
                ])
                ->required()
                ->columnSpan('full'),

            Forms\Components\Toggle::make('is_active')
                ->default(1)
                ->label('Is Active')
                ->columnSpan('full')
                ->required(),
        ];

        $provderForm = static::getProviderSettingsForm($form);
        $schema = array_merge($schema, $provderForm);

        return $form
            ->schema($schema);
    }

    public static function getProviderSettingsForm($form)
    {

        $record = ($form->getRecord());

        return [
            Forms\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    $provider = $get('provider');

                    $helpText = 'Enter the shipping cost';

                    if ($provider == 'free_shipping') {
                        $helpText = 'Enter the minimum order amount for free shipping';
                    }
                    if ($provider == 'per_item') {
                        $helpText = 'Enter the shipping cost per item';
                    }

                    if ($provider == 'local_delivery') {
                        $helpText = 'Enter the shipping cost for local delivery';
                    }

                    if ($provider == 'flat_rate') {
                        $helpText = 'Enter the flat rate shipping cost';
                    }

                    return [
                        Forms\Components\TextInput::make('shipping_cost')
                            ->maxLength(255)
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->helperText($helpText)
                            ->label('Shipping Cost'),

                        Forms\Components\Textarea::make('shipping_instructions')
                            ->label('Shipping Instructions')
                            ->default('')
                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'flat_rate'
                        or $get('provider') === 'per_item'
                        or $get('provider') === 'local_delivery'
                    );
                })
                ->columns(2)
            ,

        ];


//        return Builder::make('settings')
//            ->label(function (Forms\Get $get) {
//                return $get('provider');
//            })
//            ->live()
//            ->blocks([
//
//                Builder\Block::make('heading')
//                    ->schema([
//                        TextInput::make('content')
//                            ->label('Provider')
//                            ->default($provider)
//                            ->required(),
//                    ])
//                    ->columns(2),
//
//
//            ]);

    }


    public static function table(Table $table): Table
    {
        return $table

            ->emptyStateHeading('No Shipping Providers')
            ->emptyStateDescription('Add new shipping providers to your store.')
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
            'index' => \MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders::route('/'),
            'create' => \MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider::route('/create'),
            'edit' => \MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider::route('/{record}/edit'),
        ];
    }
}
