<?php

namespace Modules\Attributes\Filament;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Attributes\Filament\AttributeResource\Pages\CreateAttribute;
use Modules\Attributes\Filament\AttributeResource\Pages\EditAttribute;
use Modules\Attributes\Filament\AttributeResource\Pages\ListAttributes;
use Modules\Attributes\Models\Attribute;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'attribute_name';

    public static string $description = 'Custom attributes attached to products/content';

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
                        Forms\Components\TextInput::make('attribute_name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('attribute_type')
                            ->label('Type')
                            ->helperText('Optional grouping, e.g. "color", "size".')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('attribute_value')
                            ->label('Value')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('rel_type')
                            ->label('Related type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rel_id')
                            ->label('Related ID')
                            ->numeric(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attribute_name')->label('Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('attribute_value')->label('Value')->limit(40)->tooltip(fn ($state) => $state)->searchable(),
                Tables\Columns\TextColumn::make('attribute_type')->label('Type')->badge()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('rel_type')->label('Related type')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('rel_id')->label('Related ID')->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => ListAttributes::route('/'),
            'create' => CreateAttribute::route('/create'),
            'edit' => EditAttribute::route('/{record}/edit'),
        ];
    }
}
