<?php

namespace Modules\Shipping\Filament\Admin\Resources;


use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class ShippingProviderResource extends Resource
{
    protected static ?string $model = \Modules\Shipping\Models\ShippingProvider::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {


        return $form
            ->schema(function (Forms\Get $get) {
                $shippingDriversOption = [];
                $shippingDrivers = app()->shipping_method_manager->getDrivers();

                if ($shippingDrivers) {
                    foreach ($shippingDrivers as $shippingDriver) {
                        $driver = app()->shipping_method_manager->driver($shippingDriver);
                        if (!($driver->title())) {
                            continue;
                        }


                        $shippingDriversOption[$shippingDriver] = $driver->title();
                    }
                }


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
                        ->options($shippingDriversOption)
                        ->required()
                        ->columnSpan('full'),

                    Forms\Components\Toggle::make('is_active')
                        ->default(1)
                        ->label('Is Active')
                        ->columnSpan('full')
                        ->required(),
                ];


                if ($shippingDrivers) {
                    foreach ($shippingDrivers as $provider) {
                        $driver = app()->shipping_method_manager->driver($provider);
                        if (is_object($driver) and method_exists($driver, 'getSettingsForm')) {
                            $provderForm = $driver->getSettingsForm();
                            if ($provderForm) {
                                $schema = array_merge($schema, $provderForm);
                            }
                        }
                    }
                }
                return $schema;
            });
    }


    public static function table(Table $table): Table
    {
        return $table
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })
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
                Tables\Actions\DeleteAction::make()
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
            'index' => \Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders::route('/'),
            'create' => \Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider::route('/create'),
            'edit' => \Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider::route('/{record}/edit'),
        ];
    }
}
