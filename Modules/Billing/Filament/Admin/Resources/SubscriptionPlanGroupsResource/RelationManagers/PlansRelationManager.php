<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PlansRelationManager extends RelationManager
{
    protected static string $relationship = 'plans';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Plans';

    public function form(Form $form): Form
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
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('type')
                            ->label('Type')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Prices')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Displayed Price')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('discount_price')
                            ->label('Discount')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('save_price')
                            ->label('Saved Amount')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('save_price_badge')
                            ->label('Saved Amount Badge')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Subscription Settings')
                    ->schema([
                        Forms\Components\TextInput::make('billing_interval')
                            ->label('Billing Interval')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('trial_days')
                            ->label('Trial Period (days)')
                            ->numeric(),

                        Forms\Components\TextInput::make('default_interval')
                            ->label('Default Interval')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('auto_apply_coupon_code')
                            ->label('Auto Apply Coupon')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('External Settings')
                    ->schema([
                        Forms\Components\TextInput::make('remote_provider')
                            ->label('External Provider')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('remote_provider_price_id')
                            ->label('Provider Price ID')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('alternative_annual_plan_id')
                            ->label('Alternative Annual Plan')
                            ->numeric(),

                        Forms\Components\TextInput::make('position')
                            ->label('Order')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
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

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_interval')
                    ->label('Interval')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trial_days')
                    ->label('Trial Period')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Order')
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Plan'),
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
            ]);
    }
}
