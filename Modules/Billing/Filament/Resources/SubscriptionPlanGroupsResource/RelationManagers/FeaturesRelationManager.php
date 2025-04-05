<?php

namespace Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';

    protected static ?string $recordTitleAttribute = 'key';

    protected static ?string $title = 'Характеристики';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основна информация')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Ключ')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('value')
                            ->label('Стойност')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('position')
                            ->label('Позиция')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Ключ')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Стойност')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Позиция')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Добави характеристика'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Редактирай'),
                Tables\Actions\DeleteAction::make()
                    ->label('Изтрий'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Изтрий избраните'),
                ]),
            ])
            ->defaultSort('position', 'asc');
    }
}
