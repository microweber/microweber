<?php

namespace Modules\Billing\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages;


class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Subscriptions';
    protected static ?string $slug = 'subscriptions';
    protected static ?int $navigationSort = 310;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // No create/edit form for now
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('user_id')->label('User ID')->sortable(),
                TextColumn::make('customer_id')->label('Customer ID')->sortable(),
                TextColumn::make('subscription_plan_id')->label('Plan ID')->sortable(),
                TextColumn::make('stripe_id')->label('Stripe ID')->copyable()->sortable(),
                BadgeColumn::make('stripe_status')->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'canceled',
                        'warning' => 'incomplete',
                        'secondary' => 'past_due',
                    ])
                    ->sortable(),
                TextColumn::make('starts_at')->dateTime()->label('Starts At')->sortable(),
                TextColumn::make('ends_at')->dateTime()->label('Ends At')->sortable(),
                TextColumn::make('trial_ends_at')->dateTime()->label('Trial Ends At')->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Created')->sortable(),
                TextColumn::make('updated_at')->dateTime()->label('Updated')->sortable(),
            ])
            ->filters([
                SelectFilter::make('stripe_status')
                    ->options([
                        'active' => 'Active',
                        'canceled' => 'Canceled',
                        'incomplete' => 'Incomplete',
                        'past_due' => 'Past Due',
                    ]),
            ])
            ->defaultSort('id', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
        ];
    }
}
