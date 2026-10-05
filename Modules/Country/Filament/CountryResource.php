<?php

namespace Modules\Country\Filament;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Country\Filament\CountryResource\Pages\CreateCountry;
use Modules\Country\Filament\CountryResource\Pages\EditCountry;
use Modules\Country\Filament\CountryResource\Pages\ListCountries;
use Modules\Country\Models\Country;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|\UnitEnum|null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'name';

    public static string $description = 'Reference list of countries (name, code, phone code)';

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
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('code')
                            ->label('ISO code')
                            ->helperText('Two-letter ISO country code, e.g. US.')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('phonecode')
                            ->label('Phone code')
                            ->helperText('International dialing code, e.g. 1 or 44.')
                            ->maxLength(10),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('code')->label('ISO code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phonecode')->label('Phone code')->sortable()->searchable(),
            ])
            ->defaultSort('name', 'asc')
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
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'edit' => EditCountry::route('/{record}/edit'),
        ];
    }
}
