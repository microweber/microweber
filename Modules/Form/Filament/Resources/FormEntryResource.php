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

    // task-2026-06-06-AI758 — unify the surface name. This page was called
    // "Emails" (plural label → browser title, page heading, breadcrumb) while
    // the empty state + URL slug said "form submissions" / "form-entries" — three
    // names for one thing. Canonical name is "Form submissions" (matches the URL
    // and the column shape NAME/EMAIL/SUBJECT/DATE). Slug stays 'form-entries' so
    // existing bookmarks keep working.
    protected static ?string $label = 'Form submission';

    protected static ?string $pluralLabel = 'Form submissions';

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
                \Filament\Actions\ViewAction::make()
                    ->modalContent(function (FormData $record) {
                        $values = $record->getFormDataValues();
                        return view('modules.form::filament.form-entry-view', ['values' => $values, 'record' => $record]);
                    }),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No form entries yet')
            ->emptyStateDescription('Form submissions from your website visitors will appear here.')
            ->emptyStateIcon('heroicon-o-inbox');
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
