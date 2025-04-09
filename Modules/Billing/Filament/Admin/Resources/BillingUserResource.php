<?php

namespace Modules\Billing\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Billing\Filament\Resources\BillingUserResource\Pages;
use Modules\Billing\Filament\Resources\BillingUserResource\RelationManagers;
use Modules\Billing\Models\BillingUser;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Services\StripeService;

class BillingUserResource extends Resource
{
    protected static ?string $model = BillingUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3000;

    public static function form(Form $form): Form
    {
        return $form
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
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync_customers')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
