<?php

namespace Modules\Billing\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers;
use Modules\Billing\Models\SubscriptionPlanGroup;

class SubscriptionPlanGroupsResource extends Resource
{
    protected static ?string $model = SubscriptionPlanGroup::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Billing';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Plan Groups';

    protected static ?string $modelLabel = 'Plan Group';

    protected static ?string $pluralModelLabel = 'Plan Groups';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basic Information')
                    ->description('Enter the basic details of the plan group')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Professional Plans')
                            ->helperText('Display name for this plan group'),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g., PRO-PLANS')
                            ->helperText('Unique identifier for this group'),

                        Forms\Components\TextInput::make('type')
                            ->label('Type')
                            ->maxLength(255)
                            ->placeholder('e.g., premium')
                            ->helperText('Category or type of plans in this group'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->placeholder('Describe what plans in this group include...')
                            ->helperText('Detailed description of this plan group'),
                    ])->columns(2),

                Section::make('Display Settings')
                    ->description('Configure display and ordering')
                    ->schema([
                        Forms\Components\TextInput::make('position')
                            ->label('Position')
                            ->numeric()
                            ->default(0)
                            ->helperText('Display order (lower numbers first)'),

                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->maxLength(255)
                            ->placeholder('e.g., star')
                            ->helperText('Icon identifier for visual representation'),
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
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'basic' => 'Basic',
                        'standard' => 'Standard',
                        'premium' => 'Premium',
                    ]),
            ])
->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
            ->defaultSort('position', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlansRelationManager::class,
            RelationManagers\FeaturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlanGroups::route('/'),
            'create' => Pages\CreateSubscriptionPlanGroups::route('/create'),
            'edit' => Pages\EditSubscriptionPlanGroups::route('/{record}/edit'),
            'view' => Pages\ViewSubscriptionPlanGroups::route('/{record}'),
        ];
    }
}
