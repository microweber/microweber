<?php

namespace Modules\Form\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Form\Models\FormData;

class FormEntryResource extends Resource
{
    protected static ?string $model = FormData::class;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?string $label = 'Form Entry';

    protected static ?string $pluralLabel = 'Emails';

    protected static ?string $slug = 'form-entries';

    protected static bool $shouldRegisterNavigation = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['formDataValues'])
            ->orderBy('created_at', 'desc');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn (FormData $record) => $record->getFullName())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('formDataValues', function ($q) use ($search) {
                            $q->where('field_key', 'name')
                              ->where('field_value', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->getStateUsing(fn (FormData $record) => $record->getEmail())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('formDataValues', function ($q) use ($search) {
                            $q->where('field_key', 'email')
                              ->where('field_value', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->getStateUsing(fn (FormData $record) => $record->getSubject())
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function (FormData $record) {
                        $values = $record->getFormDataValues();
                        return view('modules.form::filament.form-entry-view', ['values' => $values, 'record' => $record]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Form\Filament\Resources\FormEntryResource\Pages\ListFormEntries::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
