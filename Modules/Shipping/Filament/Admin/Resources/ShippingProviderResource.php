<?php

namespace Modules\Shipping\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class ShippingProviderResource extends Resource
{
    protected static ?string $model = \Modules\Shipping\Models\ShippingProvider::class;

    protected static ?string $navigationGroup = 'Shop Settings';
    protected static ?string $navigationIcon = 'mw-shipping';

    protected static ?int $navigationSort = 5;

    public static function getAvailableToSetup()
    {
        $existingShippingProvidersNames = [];
        $existingShippingProviders = self::$model::all();
        if ($existingShippingProviders) {
            foreach ($existingShippingProviders as $existingShippingProvider) {
                $existingShippingProvidersNames[] = $existingShippingProvider->name;
            }
        }
        $shippingProviders = [];
        $shippingDrivers = app()->shipping_method_manager->getDrivers();
        if ($shippingDrivers) {
            foreach ($shippingDrivers as $shippingDriver) {
                $driver = app()->shipping_method_manager->driver($shippingDriver);
                if (in_array($driver->title(), $existingShippingProvidersNames)) {
                    continue;
                }

                $shippingProviders[$shippingDriver] = $driver->title();
            }
        }

        return [
            'shippingProviders' => $shippingProviders,
            'shippingDrivers' => $shippingDrivers
        ];
    }

    public static function form(Form $form): Form
    {
        $getAvailableToSetup = self::getAvailableToSetup();
        $shippingDrivers = $getAvailableToSetup['shippingDrivers'];
        $shippingProviders = $getAvailableToSetup['shippingProviders'];

        return $form->schema([
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Select Provider')
                    ->visible(function (Forms\Get $get) {
                        return !$get('id');
                    })
                    ->schema([
                        RadioDeck::make('provider')
                            ->live()
                            ->required()
                            ->padding('py-4 px-8')
                            ->gap('gap-0')
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, string $state) use ($shippingProviders) {
                                if ($state) {

                                    if (!$get('name')) {
                                        $set('name', $shippingProviders[$state] ?? null);
                                    }
                                    if (!$get('is_active')) {
                                        $set('is_active', 1);
                                    }
                                    if (!$get('settings')) {
                                        $set('settings', [
                                            'countries' => [
                                                [
                                                    'shipping_country' => 'Worldwide',
                                                    'shipping_type' => 'fixed',
                                                    'shipping_cost' => 0,
                                                    'is_active' => true
                                                ]
                                            ],
                                            'shipping_instructions' => 'Please select your shipping country to calculate shipping costs.'
                                        ]);
                                    }
                                }
                            })
                            ->extraCardsAttributes(['class' => 'rounded-xl'])
                            ->extraOptionsAttributes(['class' => 'text-lg leading-none w-full flex flex-col p-4'])
                            ->extraDescriptionsAttributes(['class' => 'text-sm font-light'])
                            ->iconSize(IconSize::Large)
                            ->color('primary')
                            ->options($shippingProviders)
                            ->columnSpanFull()
                            ->label('Select Provider'),
                    ]),
                Forms\Components\Wizard\Step::make('Provider Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Name')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(1)
                            ->label('Is Active')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Wizard\Step::make('Settings')
                    ->schema(function (Forms\Get $get) use ($form) {
                        $shippingDriver = $get('provider');

                        if (!$shippingDriver) {
                            return [];
                        }

                        $schemas = [];
                        $driver = app()->shipping_method_manager->driver($shippingDriver);

                        if (is_object($driver) && method_exists($driver, 'getSettingsForm')) {
                            $providerForm = $driver->getSettingsForm();
                            if ($providerForm) {
                                foreach ($providerForm as $component) {
                                    $component->columnSpanFull();
                                }
                                $schemas = array_merge($schemas, $providerForm);
                            }
                        }

                        return $schemas;
                    }),
            ])
                ->skippable()
                ->columnSpanFull(),
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
