<?php

namespace Modules\Address\Filament;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Address\Filament\AddressResource\Pages\CreateAddress;
use Modules\Address\Filament\AddressResource\Pages\EditAddress;
use Modules\Address\Filament\AddressResource\Pages\ListAddresses;
use Modules\Address\Models\Address;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    // Hidden from the sidebar (still reachable at /admin/addresses).
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static string $description = 'Customer and order addresses';

    public function getDescription(): string
    {
        return static::$description;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name / Label')
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'billing' => 'Billing',
                                'shipping' => 'Shipping',
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('address_street_1')
                            ->label('Street address')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('address_street_2')
                            ->label('Street address 2')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->label('City')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->label('State / Region')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip')
                            ->label('ZIP / Postal code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address_street_1')->label('Street')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('city')->label('City')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('state')->label('State')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('zip')->label('ZIP')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('country')->label('Country')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Phone')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('type')->label('Type')->badge()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(['billing' => 'Billing', 'shipping' => 'Shipping']),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAddresses::route('/'),
            'create' => CreateAddress::route('/create'),
            'edit' => EditAddress::route('/{record}/edit'),
        ];
    }
}
