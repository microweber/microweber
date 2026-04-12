<?php

namespace Modules\Billing\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource\RelationManagers;
use Modules\Billing\Models\BillingUser;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Services\StripeService;

class BillingUserResource extends Resource
{
    protected static ?string $model = BillingUser::class;

    protected static ?string $recordTitleAttribute = 'email';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3000;

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'username', 'first_name', 'last_name'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('subscription_plan_id')
                    ->label('Subscription Plan')
                    ->options(function() {
                        return [
                            'no_plan' => 'No Plan',
                            'free_trial' => 'Free Trial',
                            ...SubscriptionPlan::all()->pluck('name', 'id')
                        ];
                    })
                    ->required(),

                Forms\Components\Toggle::make('auto_activate_free_trial_after_date')
                    ->label('Automatically activate free trial after date'),

                Forms\Components\DatePicker::make('activate_free_trial_after_date')
                    ->label('Activate free trial after date')
                    ->format('Y-m-d')
                    ->default(null)
                    ->visible(function (callable $get) {
                        return $get('auto_activate_free_trial_after_date');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('User ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subscription')
                    ->label('Subscription')
                    ->formatStateUsing(fn (BillingUser $record) => $record->getSubscriptionName())
                    ->color(fn (BillingUser $record) => $record->hasActiveSubscription() ? 'success' : 'danger')
                    ->sortable()
                    ->searchable(),
            ])
->filters([
            SelectFilter::make('subscription_status')
                ->label('Subscription Status')
                ->options([
                    'active' => 'Active Subscription',
                    'inactive' => 'No Active Subscription',
                ])
                ->query(function (Builder $query, array $data) {
                    if (empty($data['value'])) {
                        return $query;
                    }
                    if ($data['value'] === 'active') {
                        return $query->whereHas('subscriptionManual');
                    }
                    if ($data['value'] === 'inactive') {
                        return $query->whereDoesntHave('subscriptionManual');
                    }
                }),
            Filter::make('trial_status')
                ->label('Trial Status')
                ->form([
                    Forms\Components\Select::make('trial_status')
                        ->options([
                            'scheduled' => 'Trial Scheduled',
                            'active' => 'Trial Active',
                            'expired' => 'Trial Expired',
                            'none' => 'No Trial',
                        ])
                        ->placeholder('All Users'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (empty($data['trial_status'])) {
                        return $query;
                    }
                    $status = $data['trial_status'];
                    if ($status === 'scheduled') {
                        return $query->whereHas('subscriptionManual', function ($q) {
                            $q->where('auto_activate_free_trial_after_date', true);
                        });
                    }
                    if ($status === 'active') {
                        return $query->whereNotNull('demo_started_at')
                            ->whereNull('demo_expired_at');
                    }
                    if ($status === 'expired') {
                        return $query->whereNotNull('demo_expired_at');
                    }
                    if ($status === 'none') {
                        return $query->whereNull('demo_started_at');
                    }
                }),
        ])
            ->headerActions([
                \Filament\Actions\Action::make('sync_customers')
                    ->label('Sync Customers')
                    ->color('primary')
                    ->action(function () {
                        $service = new StripeService();
                        $count = $service->syncCustomers();

                        Notification::make()
                            ->title("Synced {$count} customers with Stripe")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('impersonate')
                    ->label('Impersonate')
                    ->icon('heroicon-o-user-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Impersonate User')
                    ->modalDescription('You will be logged in as this user. Continue?')
                    ->modalSubmitActionLabel('Yes, Impersonate')
                    ->action(function (BillingUser $record) {
                        session()->put('impersonate_user_id', $record->id);
                        Notification::make()
                            ->title('Now impersonating ' . $record->email)
                            ->success()
                            ->send();
                        return redirect()->to('/');
                    })
                    ->visible(fn () => auth()->user()->can('impersonate_users')),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubscriptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\ListUsers::route('/'),
            'create' => \Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\CreateUser::route('/create'),
            'edit' => \Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }


}
