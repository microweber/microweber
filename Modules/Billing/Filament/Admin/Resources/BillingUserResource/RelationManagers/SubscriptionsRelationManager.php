<?php

namespace Modules\Billing\Filament\Admin\Resources\BillingUserResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Modules\Billing\Models\Subscription;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'User Subscriptions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Subscription Name')
                    ->disabled(),
                Forms\Components\TextInput::make('stripe_status')
                    ->label('Status')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),
            Tables\Columns\TextColumn::make('stripe_status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active', 'trialing' => 'success',
                    'canceled', 'unpaid', 'incomplete_expired', 'expired' => 'danger',
                    'incomplete', 'past_due' => 'warning',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => ucfirst($state))
                ->sortable(),
                Tables\Columns\TextColumn::make('stripe_price')
                    ->label('Price ID')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Starts At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Ends At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Trial Ends')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('stripe_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'canceled' => 'Canceled',
                        'expired' => 'Expired',
                        'incomplete' => 'Incomplete',
                        'past_due' => 'Past Due',
                        'trialing' => 'Trialing',
                        'unpaid' => 'Unpaid',
                    ]),
            ])
            ->headerActions([
                // No create action - subscriptions are created via Stripe
            ])
->actions([
            ViewAction::make(),
        ])
            ->bulkActions([
                // No bulk actions - subscriptions managed via Stripe
            ]);
    }
}
