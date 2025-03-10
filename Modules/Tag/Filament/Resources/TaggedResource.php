<?php

namespace Modules\Tag\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Tag\Models\Tagged;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Modules\Tag\Filament\Resources\TaggedResource\Pages;
use Modules\Tag\Models\Tag;

class TaggedResource extends Resource
{
    protected static ?string $model = Tagged::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 92;

    protected static ?string $label = 'Tagged Content';

    protected static ?string $pluralLabel = 'Tagged Content';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('taggable_id')
                    ->required()
                    ->numeric()
                    ->label('Content ID'),
                TextInput::make('taggable_type')
                    ->required()
                    ->default('content')
                    ->label('Content Type'),
                Select::make('tag_name')
                    ->label('Tag')
                    ->options(function () {
                        return Tag::pluck('name', 'name')->toArray();
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('taggable_id')
                    ->label('Content ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('taggable_type')
                    ->label('Content Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tag_name')
                    ->label('Tag')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tag_slug')
                    ->label('Tag Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagged::route('/'),
            'create' => Pages\CreateTagged::route('/create'),
            'edit' => Pages\EditTagged::route('/{record}/edit'),
        ];
    }
}
