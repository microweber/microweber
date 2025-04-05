<?php

namespace Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PlansRelationManager extends RelationManager
{
    protected static string $relationship = 'plans';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Планове';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основна информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Име')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('type')
                            ->label('Тип')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Цени')
                    ->schema([
                        Forms\Components\TextInput::make('display_price')
                            ->label('Показвана цена')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('discount_price')
                            ->label('Отстъпка')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('save_price')
                            ->label('Спестена сума')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('save_price_badge')
                            ->label('Бейдж за спестена сума')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Настройки на абонамента')
                    ->schema([
                        Forms\Components\TextInput::make('billing_interval')
                            ->label('Интервал на плащане')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('trial_days')
                            ->label('Пробен период (дни)')
                            ->numeric(),

                        Forms\Components\TextInput::make('default_interval')
                            ->label('Интервал по подразбиране')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('auto_apply_coupon_code')
                            ->label('Автоматичен купон')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Външни настройки')
                    ->schema([
                        Forms\Components\TextInput::make('remote_provider')
                            ->label('Външен доставчик')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('remote_provider_price_id')
                            ->label('ID на цена от доставчик')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('alternative_annual_plan_id')
                            ->label('Алтернативен годишен план')
                            ->numeric(),

                        Forms\Components\TextInput::make('position')
                            ->label('Подредба')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Име')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_price')
                    ->label('Цена')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_interval')
                    ->label('Интервал')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trial_days')
                    ->label('Пробен период')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Подредба')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'monthly' => 'Месечен',
                        'yearly' => 'Годишен',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Добави план'),
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
            ]);
    }
}
