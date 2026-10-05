<?php

namespace Modules\ContentData\Filament;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGloballySearchable;
use Modules\ContentData\Filament\ContentDataResource\Pages\CreateContentData;
use Modules\ContentData\Filament\ContentDataResource\Pages\EditContentData;
use Modules\ContentData\Filament\ContentDataResource\Pages\ListContentData;
use Modules\ContentData\Models\ContentData;

class ContentDataResource extends Resource
{
    use MicroweberGloballySearchable;

    protected static ?string $model = ContentData::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Content data';

    protected static ?string $modelLabel = 'content data entry';

    protected static ?string $pluralModelLabel = 'content data';

    protected static ?string $recordTitleAttribute = 'field_name';

    public static string $description = 'Custom key/value data attached to content (rel_type / rel_id)';

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
                        Forms\Components\TextInput::make('field_name')
                            ->label('Field name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('rel_type')
                            ->label('Related type')
                            ->helperText('The model this data belongs to, e.g. "content".')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('rel_id')
                            ->label('Related ID')
                            ->numeric()
                            ->helperText('ID of the related record.'),

                        Forms\Components\Textarea::make('field_value')
                            ->label('Field value')
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
                Tables\Columns\TextColumn::make('field_name')
                    ->label('Field')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('field_value')
                    ->label('Value')
                    ->limit(50)
                    ->tooltip(fn ($state) => $state)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('rel_type')
                    ->label('Related type')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rel_id')
                    ->label('Related ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rel_type')
                    ->label('Related type')
                    ->options(fn () => ContentData::query()
                        ->whereNotNull('rel_type')
                        ->distinct()
                        ->orderBy('rel_type')
                        ->pluck('rel_type', 'rel_type')
                        ->all()),
            ])
            ->defaultSort('updated_at', 'desc')
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
            'index' => ListContentData::route('/'),
            'create' => CreateContentData::route('/create'),
            'edit' => EditContentData::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['field_name', 'field_value', 'rel_type'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->field_name ?? 'Content data #' . $record->id;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Value' => \Illuminate\Support\Str::limit((string) $record->field_value, 60),
            'Related' => trim(($record->rel_type ?? '') . ' ' . ($record->rel_id ?? '')),
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record->id])),
        ];
    }
}
