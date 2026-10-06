<?php

namespace Modules\ContentField\Filament;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\ContentField\Filament\ContentFieldResource\Pages\CreateContentField;
use Modules\ContentField\Filament\ContentFieldResource\Pages\EditContentField;
use Modules\ContentField\Filament\ContentFieldResource\Pages\ListContentFields;
use Modules\ContentField\Models\ContentField;

class ContentFieldResource extends Resource
{
    protected static ?string $model = ContentField::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    // Hidden from the sidebar (still reachable at /admin/content-fields).
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'Content fields';

    protected static ?string $modelLabel = 'content field';

    protected static ?string $recordTitleAttribute = 'field';

    public static string $description = 'Field / value data attached to content (rel_type / rel_id)';

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
                        Forms\Components\TextInput::make('field')
                            ->label('Field')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rel_type')
                            ->label('Related type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rel_id')
                            ->label('Related ID')
                            ->numeric(),
                        Forms\Components\Textarea::make('value')
                            ->label('Value')
                            ->rows(5)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('field')->label('Field')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('value')->label('Value')->limit(50)->tooltip(fn ($state) => $state)->searchable()->wrap(),
                Tables\Columns\TextColumn::make('rel_type')->label('Related type')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('rel_id')->label('Related ID')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('rel_type')
                    ->label('Related type')
                    ->options(fn () => ContentField::query()
                        ->whereNotNull('rel_type')
                        ->distinct()
                        ->orderBy('rel_type')
                        ->pluck('rel_type', 'rel_type')
                        ->all()),
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
            'index' => ListContentFields::route('/'),
            'create' => CreateContentField::route('/create'),
            'edit' => EditContentField::route('/{record}/edit'),
        ];
    }
}
