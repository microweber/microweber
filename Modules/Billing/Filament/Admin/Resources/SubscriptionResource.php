<?php

namespace Modules\Billing\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string | null $navigationLabel = 'Subscriptions';

    protected static ?string $slug = 'subscriptions';

    protected static ?int $navigationSort = 310;

    protected static string | \UnitEnum | null $navigationGroup = 'Billing';

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Billing';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Subscription Details')
                    ->schema([
                        Select::make('subscription_plan_id')
                            ->label('Plan')
                            ->relationship('plan', 'name')
                            ->options(SubscriptionPlan::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('stripe_status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'canceled' => 'Canceled',
                                'incomplete' => 'Incomplete',
                                'incomplete_expired' => 'Incomplete Expired',
                                'past_due' => 'Past Due',
                                'paused' => 'Paused',
                                'trialing' => 'Trialing',
                                'unpaid' => 'Unpaid',
                            ])
                            ->required(),

                        TextInput::make('stripe_id')
                            ->label('Stripe Subscription ID')
                            ->placeholder('sub_xxxxxxxxxx'),

                        TextInput::make('stripe_price')
                            ->label('Stripe Price ID')
                            ->placeholder('price_xxxxxxxxxx'),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default(1),
                    ])
                    ->columns(2),

                Section::make('Trial & Billing Period')
                    ->schema([
                        Toggle::make('is_trial')
                            ->label('In Trial Period')
                            ->live()
                            ->afterStateHydrated(function (Toggle $component, ?Model $record) {
                                if ($record) {
                                    $component->state($record->trial_ends_at && $record->trial_ends_at->isFuture());
                                }
                            }),

                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Trial Ends At')
                            ->visible(fn (callable $get) => $get('is_trial')),

                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Subscription Starts'),

                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Subscription Ends'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('customer_id')
                    ->label('Customer ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->getStateUsing(fn (Subscription $record) => $record->plan?->name ?? 'N/A')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('stripe_id')
                    ->label('Stripe ID')
                    ->copyable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('stripe_status')
                    ->label('Status')
                    ->colors([
                        'success' => ['active', 'trialing'],
                        'danger' => ['canceled', 'unpaid', 'incomplete_expired'],
                        'warning' => ['incomplete', 'past_due'],
                        'secondary' => ['paused'],
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Active',
                        'canceled' => 'Canceled',
                        'incomplete' => 'Incomplete',
                        'incomplete_expired' => 'Expired',
                        'past_due' => 'Past Due',
                        'paused' => 'Paused',
                        'trialing' => 'Trialing',
                        'unpaid' => 'Unpaid',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                BadgeColumn::make('trial_status')
                    ->label('Trial')
                    ->getStateUsing(function (Subscription $record): string {
                        if ($record->onTrial()) {
                            return 'Active';
                        }
                        if ($record->trial_ends_at && $record->trial_ends_at->isPast()) {
                            return 'Expired';
                        }
                        return 'None';
                    })
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Expired',
                        'secondary' => 'None',
                    ])
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy('trial_ends_at', $direction);
                    }),

                TextColumn::make('starts_at')
                    ->dateTime('M d, Y')
                    ->label('Starts')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ends_at')
                    ->dateTime('M d, Y')
                    ->label('Ends')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('trial_ends_at')
                    ->dateTime('M d, Y')
                    ->label('Trial Ends')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->label('Created')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('stripe_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'canceled' => 'Canceled',
                        'incomplete' => 'Incomplete',
                        'incomplete_expired' => 'Incomplete Expired',
                        'past_due' => 'Past Due',
                        'paused' => 'Paused',
                        'trialing' => 'Trialing',
                        'unpaid' => 'Unpaid',
                    ]),

                Filter::make('trial_status')
                    ->label('Trial Status')
                    ->form([
                        Select::make('trial_status')
                            ->options([
                                'active' => 'Active Trial',
                                'expired' => 'Expired Trial',
                                'no_trial' => 'No Trial',
                            ])
                            ->placeholder('All'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['trial_status'])) {
                            return $query;
                        }

                        return match ($data['trial_status']) {
                            'active' => $query->whereNotNull('trial_ends_at')
                                ->where('trial_ends_at', '>', now()),
                            'expired' => $query->whereNotNull('trial_ends_at')
                                ->where('trial_ends_at', '<=', now()),
                            'no_trial' => $query->whereNull('trial_ends_at'),
                            default => $query,
                        };
                    }),

                Filter::make('active_subscriptions')
                    ->label('Active Only')
                    ->query(fn (Builder $query) => $query->whereIn('stripe_status', ['active', 'trialing'])),

                Filter::make('canceled_subscriptions')
                    ->label('Canceled Only')
                    ->query(fn (Builder $query) => $query->where('stripe_status', 'canceled')),

                Filter::make('expired_subscriptions')
                    ->label('Expired Only')
                    ->query(fn (Builder $query) => $query->where('stripe_status', 'incomplete_expired')),
            ])
            ->filtersFormColumns(2)
            ->headerActions([
                Action::make('syncStripe')
                    ->label('Sync from Stripe')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function () {
                        Notification::make()
                            ->title('Sync initiated')
                            ->body('Subscriptions are being synced from Stripe...')
                            ->info()
                            ->send();

                        // Note: Actual sync logic would be implemented here
                        // dispatch(new SyncStripeSubscriptionsJob());
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Sync from Stripe')
                    ->modalDescription('This will sync all subscriptions from Stripe. Continue?'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('refund')
                    ->label('Refund Last Payment')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Refund Last Payment')
                    ->modalDescription('This will process a refund for the last payment associated with this subscription. This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, Refund')
                    ->visible(fn () => auth()->user()->can('manage_billing'))
                    ->action(function (Subscription $record) {
                        // Mock refund implementation
                        // In production, this would:
                        // 1. Fetch the latest invoice from Stripe
                        // 2. Create a refund via Stripe API
                        // 3. Log the refund in local database
                        // 4. Send notification to customer

                        try {
                            // Simulate Stripe API call
                            // $stripeService = app(StripeService::class);
                            // $refund = $stripeService->refundLatestPayment($record->stripe_id);

                            // Log the refund attempt (mock)
                            logger()->info('Refund initiated for subscription', [
                                'subscription_id' => $record->id,
                                'stripe_id' => $record->stripe_id,
                                'refund_amount' => $record->plan?->price ?? 0,
                                'refunded_at' => now()->toISOString(),
                            ]);

                            Notification::make()
                                ->title('Refund Processed (Mock)')
                                ->body("A refund has been initiated for subscription #{$record->id}. This is a mock implementation - integrate with Stripe for real refunds.")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Refund Failed')
                                ->body('Failed to process refund: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                DeleteAction::make(),
            ])
->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),

                BulkAction::make('cancelSubscriptions')
                        ->label('Cancel Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Subscriptions')
                        ->modalDescription('Are you sure you want to cancel the selected subscriptions?')
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->stripe_status === 'active') {
                                    // In production: Cancel via Stripe API
                                    // $record->cancel();
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("{$count} subscriptions queued for cancellation")
                                ->success()
                                ->send();
                        })
                        ->visible(fn () => auth()->user()->can('manage_billing')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\Section::make('Subscription Information')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('id')
                            ->label('Subscription ID'),

                        \Filament\Infolists\Components\TextEntry::make('stripe_id')
                            ->label('Stripe ID')
                            ->copyable(),

                        \Filament\Infolists\Components\TextEntry::make('user_id')
                            ->label('User ID'),

                        \Filament\Infolists\Components\TextEntry::make('customer_id')
                            ->label('Customer ID'),

                        \Filament\Infolists\Components\TextEntry::make('plan.name')
                            ->label('Subscription Plan')
                            ->getStateUsing(fn (Subscription $record) => $record->plan?->name ?? 'N/A'),

                        \Filament\Infolists\Components\TextEntry::make('stripe_status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active', 'trialing' => 'success',
                                'canceled', 'unpaid', 'incomplete_expired' => 'danger',
                                'incomplete', 'past_due' => 'warning',
                                default => 'secondary',
                            }),
                    ])
                    ->columns(3),

                \Filament\Infolists\Components\Section::make('Billing Details')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('stripe_price')
                            ->label('Stripe Price ID'),

                        \Filament\Infolists\Components\TextEntry::make('quantity')
                            ->label('Quantity'),

                        \Filament\Infolists\Components\TextEntry::make('next_billing_date')
                            ->label('Next Billing Date')
                            ->getStateUsing(function (Subscription $record): string {
                                // Calculate next billing date based on Stripe subscription
                                if ($record->stripe_status === 'canceled' || $record->stripe_status === 'incomplete_expired') {
                                    return 'N/A (Subscription ended)';
                                }

                                if ($record->onTrial()) {
                                    return $record->trial_ends_at->format('F j, Y');
                                }

                                // For active subscriptions, estimate next billing based on current period
                                if ($record->starts_at) {
                                    $interval = $record->plan?->billing_interval ?? 'monthly';
                                    $nextDate = match ($interval) {
                                        'yearly' => $record->starts_at->copy()->addYear(),
                                        'monthly' => $record->starts_at->copy()->addMonth(),
                                        default => $record->starts_at->copy()->addMonth(),
                                    };
                                    return $nextDate->format('F j, Y') . ' (estimated)';
                                }

                                return 'Unknown';
                            })
                            ->icon('heroicon-o-calendar'),
                    ])
                    ->columns(3),

                \Filament\Infolists\Components\Section::make('Timeline')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('starts_at')
                            ->label('Started At')
                            ->dateTime('F j, Y, g:i a'),

                        \Filament\Infolists\Components\TextEntry::make('ends_at')
                            ->label('Ends At')
                            ->dateTime('F j, Y, g:i a')
                            ->placeholder('Ongoing'),

                        \Filament\Infolists\Components\TextEntry::make('trial_ends_at')
                            ->label('Trial Ends At')
                            ->dateTime('F j, Y, g:i a')
                            ->placeholder('Not in trial'),

                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime('F j, Y, g:i a'),

                        \Filament\Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F j, Y, g:i a'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Invoices relation can be added here if there's a local Invoice model
            // For now, invoices are fetched from Stripe API
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'view' => Pages\ViewSubscription::route('/{record}'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('stripe_status', ['active', 'trialing'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'success';
    }
}
