<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';

    protected static ?string $recordTitleAttribute = 'key';

    protected static ?string $title = 'Features';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('position')
                            ->label('Position')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
->headerActions([
            CreateAction::make()
                ->label('Add Feature'),
        ])
        ->actions([
            EditAction::make()
                ->label('Edit'),
            DeleteAction::make()
                ->label('Delete'),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->label('Delete Selected'),
            ]),
        ])
            ->defaultSort('position', 'asc');
    }
}
