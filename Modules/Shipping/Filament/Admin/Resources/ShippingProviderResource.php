<?php

namespace Modules\Shipping\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class ShippingProviderResource extends Resource
{
    protected static ?string $model = \Modules\Shipping\Models\ShippingProvider::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-truck';
    protected static string | null $navigationLabel = 'Shipping Providers';
    protected static ?int $navigationSort = 15;

    protected static bool $isGloballySearchable = true;

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'provider'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->provider) {
            $details['Provider'] = $record->provider;
        }

        $details['Status'] = $record->is_active ? 'Active' : 'Inactive';

        return $details;
    }

    public static string $description = 'Configure your shop Shipping Providers';
    public function getDescription(): string
    {

        return static::$description;
    }


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

    public static function form(Schema $schema): Schema
    {
        $getAvailableToSetup = self::getAvailableToSetup();
        $shippingDrivers = $getAvailableToSetup['shippingDrivers'];
        $shippingProviders = $getAvailableToSetup['shippingProviders'];

        return $schema->schema([
            \Filament\Schemas\Components\Wizard::make([
                \Filament\Schemas\Components\Wizard\Step::make('Select Provider')
                    ->visible(function (\Filament\Schemas\Components\Utilities\Get $get) {
                        return !$get('id');
                    })
                    ->schema([
                        RadioDeck::make('provider')
                            ->live()
                            ->required()
                            ->padding('py-4 px-8')
                            ->gap('gap-0')
                            ->afterStateUpdated(function (\Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, string $state) use ($shippingProviders) {
                                if ($state) {

                                 if (!$get('id') and !$get('name')) {
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
                                            'shipping_instructions' => 'Please select your shipping country.'
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
                \Filament\Schemas\Components\Wizard\Step::make('Provider Details')
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
                \Filament\Schemas\Components\Wizard\Step::make('Settings')
                    ->schema(function (\Filament\Schemas\Components\Utilities\Get $get) use ($schema) {
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
            // task-2026-05-28-f3a1d8 / AI-1064 — replaced content-module empty-state view
            // (which rendered retail shopper imagery) with Filament built-in empty-state
            // methods scoped to the shipping context.
            ->emptyStateIcon('heroicon-o-truck')
            ->emptyStateHeading('No shipping providers configured')
            ->emptyStateDescription('Add a shipping provider to offer delivery options at checkout.')
            ->emptyStateActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Add shipping provider')
                    ->icon('heroicon-m-plus'),
            ])
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
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
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
            'index' => \Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders::route('/'),
            'create' => \Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider::route('/create'),
            'edit' => \Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider::route('/{record}/edit'),
        ];
    }
}
