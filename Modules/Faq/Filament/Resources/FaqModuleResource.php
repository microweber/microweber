<?php

namespace Modules\Faq\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Faq\Models\Faq;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages;

class FaqModuleResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $recordTitleAttribute = 'question';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string | \UnitEnum | null $navigationGroup = 'Other';

    protected static ?int $navigationSort = 100;

    protected static bool $shouldRegisterNavigation = false;

    public static function getGloballySearchableAttributes(): array
    {
        return ['question', 'answer'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->answer) {
            $details['Answer'] = \Illuminate\Support\Str::limit($record->answer, 80);
        }

        return $details;
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('question')
                    ->required()
                    ->maxLength(255),
                Textarea::make('answer')
                    ->required()
                    ->rows(4),
                TextInput::make('position')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
                TextInput::make('rel_type')
                    ->maxLength(255),
                TextInput::make('rel_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('answer')
                    ->limit(50),
                TextColumn::make('position')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->sortable(),
                TextColumn::make('rel_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rel_id')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
