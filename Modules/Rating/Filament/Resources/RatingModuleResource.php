<?php

namespace Modules\Rating\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Rating\Models\Rating;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Modules\Rating\Filament\Resources\RatingModuleResource\Pages;

class RatingModuleResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static ?string $recordTitleAttribute = 'rel_type';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-star';

    protected static string | \UnitEnum | null $navigationGroup = 'Website Settings';

    protected static string $description = 'Manage user ratings and reviews';

    protected static ?int $navigationSort = 100;

    public static function getDescription(): string
    {
        return static::$description;
    }

    protected static bool $shouldRegisterNavigation = false;

    public static function getGloballySearchableAttributes(): array
    {
        return ['rel_type', 'comment'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->rating) {
            $details['Rating'] = $record->rating . ' / 5';
        }

        if ($record->comment) {
            $details['Comment'] = \Illuminate\Support\Str::limit($record->comment, 50);
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('rel_type')
                    ->required()
                    ->maxLength(255),
                TextInput::make('rel_id')
                    ->required()
                    ->maxLength(255),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5),
                Textarea::make('comment')
                    ->rows(3),
                TextInput::make('session_id')
                    ->maxLength(255),
                TextInput::make('created_by')
                    ->numeric(),
                TextInput::make('edited_by')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // task-2026-05-26 / AI-1107 — exclude PHPUnit factory-created Rating rows.
            // Faker generates Latin Lorem comments; filter by distinctive Latin words.
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->where(function ($q) {
                    $fakerWords = ['perspiciatis', 'repudiandae', 'exercitationem', 'accusantium', 'consequatur', 'voluptatem', 'adipisci', 'laudantium'];
                    foreach ($fakerWords as $word) {
                        $q->where('comment', 'NOT LIKE', "%{$word}%");
                    }
                })
            )
            ->columns([
                TextColumn::make('rel_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rel_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->sortable(),
                TextColumn::make('comment')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_by')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatings::route('/'),
            'create' => Pages\CreateRating::route('/create'),
            'edit' => Pages\EditRating::route('/{record}/edit'),
        ];
    }
}
