<?php

namespace Modules\Billing\Filament\Resources;

use Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\Pages;
use Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Illuminate\Support\Str;

class SubscriptionPlanGroupsResource extends Resource
{
    protected static ?string $model = SubscriptionPlanGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort =4;

    protected static ?string $navigationLabel = 'Plan Groups';

    protected static ?string $modelLabel = 'Plan Group';

    protected static ?string $pluralModelLabel = 'Plan Groups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('type')
                            ->label('Type')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('position')
                            ->label('Position')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Selected'),
                ]),
            ])
            ->defaultSort('position', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlansRelationManager::make(),
            RelationManagers\FeaturesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlanGroups::route('/'),
            'create' => Pages\CreateSubscriptionPlanGroups::route('/create'),
            'edit' => Pages\EditSubscriptionPlanGroups::route('/{record}/edit'),
        ];
    }
}
