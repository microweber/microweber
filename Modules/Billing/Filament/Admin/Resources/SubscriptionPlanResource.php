<?php

namespace Modules\Billing\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Billing\Filament\Resources\SubscriptionPlanResource\Pages;
use Modules\Billing\Filament\Resources\SubscriptionPlanResource\RelationManagers;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Services\StripeService;
use Modules\Payment\Models\PaymentProvider;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('Enter the basic details of your subscription plan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('A descriptive name for your subscription plan')
                            ->placeholder('e.g., Professional Plan'),
                        Forms\Components\TextInput::make('sku')
                            ->required()
                            ->reactive()
                            ->live()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull()
                            ->helperText('A unique identifier for this plan')
                            ->placeholder('e.g., PRO-PLAN-MONTHLY'),
                        Forms\Components\Select::make('subscription_plan_group_id')
                            ->relationship('group', 'name')
                            //   ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->helperText('Name of the plan group (e.g., Business Plans)'),
                            ])
                            ->columnSpanFull()
                            ->helperText('Group this plan belongs to'),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->helperText('Detailed description of what this plan includes')
                            ->placeholder('Describe the features and benefits of this plan...'),
                    ])->columns(1),

                Forms\Components\Section::make('Pricing')
                    ->description('Configure the pricing details for this plan')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->columnSpanFull()
                            ->helperText('The regular price shown to customers')
                            ->minValue(0),
                        Forms\Components\TextInput::make('discount_price')
                            ->numeric()
                            ->prefix('$')
                            ->columnSpanFull()
                            ->helperText('Optional discounted price')
                            ->minValue(0)
                            ->visible(fn(Forms\Get $get) => $get('price') > 0),
                        Forms\Components\TextInput::make('save_price')
                            ->numeric()
                            ->prefix('$')
                            ->columnSpanFull()
                            ->helperText('Amount customers save (calculated automatically)')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn(Forms\Get $get) => $get('price') > 0 && $get('discount_price') > 0),
                        Forms\Components\TextInput::make('save_price_badge')
                            ->columnSpanFull()
                            ->helperText('Text to display for savings (e.g., "Save 20%")')
                            ->visible(fn(Forms\Get $get) => $get('price') > 0 && $get('discount_price') > 0),
                        Forms\Components\Select::make('billing_interval')
                            ->options([
                                'month' => 'Month',
                                'year' => 'Year',
                             ])
                            ->required()
                            ->columnSpanFull()
                            ->helperText('How often customers will be billed'),
                        Forms\Components\TextInput::make('alternative_annual_plan_id')
                            ->numeric()
                            ->columnSpanFull()
                            ->helperText('ID of the annual version of this plan (if applicable)')
                            ->visible(fn(Forms\Get $get) => in_array($get('billing_interval'), ['monthly', 'yearly'])),
                    ])->columns(1),

                Forms\Components\Section::make('Integration')
                    ->description('Configure external service integration details')
                    ->schema([
                        Forms\Components\Select::make('remote_provider')
                            ->reactive()
                            ->options([
                                'stripe' => 'Stripe',

                            ])
                            ->columnSpanFull()
                            ->helperText('The payment provider this plan is integrated with'),

                        Forms\Components\Select::make('remote_provider_id')
                            ->reactive()
                            ->options(function (callable $get) {
                                $provider = $get('remote_provider');
                                if (!$provider) {
                                    return [];
                                }
                                return PaymentProvider::where('is_active', 1)
                                    ->where('provider', $provider)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->columnSpanFull()
                            ->helperText('Select the payment provider instance')
                            ->visible(fn ($get) => filled($get('remote_provider'))),

                        Forms\Components\TextInput::make('remote_provider_price_id')
                            ->columnSpanFull()
                            ->reactive()
                            ->helperText('The price ID from your payment provider')
                            ->visible(fn ($get) => filled($get('remote_provider_id'))),

                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn(SubscriptionPlan $record): string => $record->description ?? '')
                    ->wrap(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('SKU copied to clipboard')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('group.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->sortable()
                    ->description(fn(SubscriptionPlan $record): string => $record->discount_price ? "Regular: $" . number_format($record->price, 2) : ''
                    ),
                Tables\Columns\TextColumn::make('billing_interval')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'monthly' => 'gray',
                        'yearly' => 'success',
                        'annually' => 'success',
                        'lifetime' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('remote_provider')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'stripe' => 'indigo',
                        'paypal' => 'blue',
                        'paddle' => 'orange',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->relationship('group', 'name'),
                Tables\Filters\SelectFilter::make('billing_interval')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                        'annually' => 'Annually',
                        'lifetime' => 'Lifetime',
                    ]),
                Tables\Filters\SelectFilter::make('remote_provider')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'paddle' => 'Paddle',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Sync from Stripe')
                    ->label('Sync from Stripe')
                    ->color('primary')
                    ->action(function () {


                        // Sync products from Stripe
                        $service = app()->make(\Modules\Billing\Services\StripeService::class);
                        /**
                         * @var StripeService $service
                         */
                        $count = $service->syncProducts();

                        Notification::make()
                            ->success()
                            ->title("Synced {$count} products from Stripe.")
                            ->send();
                    }),
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
            'index' => \Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\ListSubscriptionPlans::route('/'),
            'create' => \Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => \Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }


}
