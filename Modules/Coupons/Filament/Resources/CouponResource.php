<?php

namespace Modules\Coupons\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Modules\Coupons\Filament\Resources\CouponResource\Pages;
use Modules\Coupons\Filament\Resources\CouponResource\RelationManagers;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Services\CouponService;
use MicroweberPackages\Filament\Support\AdminFixtureGuard;

class CouponResource extends Resource
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $model = Coupon::class;
    protected static ?string $recordTitleAttribute = 'coupon_name';
    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static ?int $navigationSort = 12;

    protected static string $description = 'Configure your shop coupons settings';

    public static function getGloballySearchableAttributes(): array
    {
        return ['coupon_name', 'coupon_code', 'description'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->coupon_code) {
            $details['Code'] = $record->coupon_code;
        }

        if ($record->discount_type && $record->discount_value) {
            $details['Discount'] = $record->discount_type === 'percentage'
                ? $record->discount_value . '%'
                : '$' . $record->discount_value;
        }

        return $details;
    }

    public static function getDescription(): string
    {
        return static::$description;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basic Information')
                    ->icon('heroicon-m-ticket')
                    ->description('Configure the coupon name, code, and description.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('coupon_name')
                                ->label('Coupon Name')
                                ->required()
                                ->maxLength(255),

                        TextInput::make('coupon_code')
                            ->label('Coupon Code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Internal notes about this coupon...')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Discount Settings')
                    ->icon('heroicon-m-receipt-percent')
                    ->description('Configure how the discount is calculated.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('discount_type')
                                ->label('Discount Type')
                                ->live()
                                ->options([
                                    'percentage' => 'Percentage',
                                    'fixed_amount' => 'Fixed Amount',
                                    'free_shipping' => 'Free Shipping',
                                ])
                                ->required(),

                            TextInput::make('discount_value')
                                ->label('Discount Value')
                                ->required(fn ($get) => $get('discount_type') !== 'free_shipping')
                                ->disabled(fn ($get) => $get('discount_type') === 'free_shipping')
                                ->live()
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(fn($get) => $get('discount_type') === 'percentage' ? 100 : null),

                            TextInput::make('max_discount_amount')
                                ->label('Maximum Discount Amount')
                                ->helperText('For percentage coupons, the maximum discount amount.')
                                ->numeric()
                                ->minValue(0)
                                ->visible(fn($get) => $get('discount_type') === 'percentage'),

                        TextInput::make('total_amount')
                            ->label('Minimum Order Amount')
                            ->numeric()
                            ->minValue(0)
                            ->suffix(fn() => function_exists('currency_symbol') ? currency_symbol() : '$'),
                        ]),
                    ]),

                Section::make('Usage Limits')
                    ->icon('heroicon-m-calculator')
                    ->description('Configure who can use this coupon and how many times.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('uses_per_coupon')
                                ->label('Total Usage Limit')
                                ->helperText('Leave empty for unlimited uses.')
                                ->numeric()
                                ->minValue(0)
                                ->placeholder('Unlimited'),

                            TextInput::make('uses_per_customer')
                                ->label('Uses Per Customer')
                                ->helperText('Leave empty for unlimited uses per customer.')
                                ->numeric()
                                ->minValue(0)
                                ->placeholder('Unlimited'),
                        ]),
                    ]),

Section::make('Item Count Requirements')
                ->icon('heroicon-m-shopping-cart')
                ->description('Configure minimum and maximum item count requirements.')
                ->collapsible()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('min_items_count')
                            ->label('Minimum Items')
                            ->helperText('Minimum number of items required in cart.')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('No minimum'),

                        TextInput::make('max_items_count')
                            ->label('Maximum Items')
                            ->helperText('Maximum number of items allowed in cart.')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('No maximum'),
                    ]),

                    TextInput::make('max_discount_per_order')
                        ->label('Maximum Discount Per Order')
                        ->helperText('Maximum discount amount that can be applied per order.')
                        ->numeric()
                        ->minValue(0)
                        ->prefix(fn() => function_exists('currency_symbol') ? currency_symbol() : '$')
                        ->placeholder('No limit'),
                ]),

            Section::make('Buy X Get Y (BOGO) Rules')
                ->icon('heroicon-m-gift')
                ->description('Configure buy X get Y discount rules.')
                ->collapsible()
                ->schema([
                    Toggle::make('bogo_enabled')
                        ->label('Enable BOGO')
                        ->default(false)
                        ->live(),

                    Grid::make(3)->schema([
                        TextInput::make('bogo_buy_quantity')
                            ->label('Buy Quantity (X)')
                            ->helperText('Number of items to buy.')
                            ->numeric()
                            ->minValue(1)
                            ->required(fn($get) => $get('bogo_enabled')),

                        TextInput::make('bogo_get_quantity')
                            ->label('Get Quantity (Y)')
                            ->helperText('Number of items to get free/discounted.')
                            ->numeric()
                            ->minValue(1)
                            ->required(fn($get) => $get('bogo_enabled')),

                        TextInput::make('bogo_discount_percent')
                            ->label('Discount % on Free Items')
                            ->helperText('100 = Free, 50 = 50% off.')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(100)
                            ->suffix('%'),
                    ])
                    ->visible(fn($get) => $get('bogo_enabled')),

                    Select::make('bogo_apply_to')
                        ->label('Apply BOGO To')
                        ->options([
                            'matching' => 'Matching Products',
                            'cheapest' => 'Cheapest Items',
                            'expensive' => 'Most Expensive Items',
                        ])
                        ->default('matching')
                        ->visible(fn($get) => $get('bogo_enabled')),
                ]),

            Section::make('Tiered/Volume Discount')
                ->icon('heroicon-m-chart-bar')
                ->description('Configure tiered discounts based on cart total or item count.')
                ->collapsible()
                ->schema([
                    Toggle::make('tiered_enabled')
                        ->label('Enable Tiered Discounts')
                        ->default(false)
                        ->live(),

                    Forms\Components\Repeater::make('tiered_rules')
                        ->label('Discount Tiers')
                        ->helperText('Define discount tiers. Higher thresholds override lower ones.')
                        ->schema([
                            Grid::make(3)->schema([
                                Select::make('type')
                                    ->label('Threshold Type')
                                    ->options([
                                        'amount' => 'Cart Total',
                                        'quantity' => 'Item Count',
                                    ])
                                    ->required(),

                                TextInput::make('threshold')
                                    ->label('Threshold')
                                    ->helperText('Minimum amount or count to reach this tier.')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),

                                Select::make('discount_type')
                                    ->label('Discount Type')
                                    ->options([
                                        'percentage' => 'Percentage',
                                        'fixed' => 'Fixed Amount',
                                    ])
                                    ->default('percentage')
                                    ->required(),

                                TextInput::make('discount_value')
                                    ->label('Discount Value')
                                    ->helperText('Percentage or fixed amount.')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),

                                TextInput::make('max_discount')
                                    ->label('Maximum Discount')
                                    ->helperText('Maximum discount for percentage coupons.')
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('No limit')
                                    ->visible(fn($get) => $get('discount_type') === 'percentage'),
                            ]),
                        ])
                        ->addable()
                        ->deletable()
                        ->reorderable()
                        ->visible(fn($get) => $get('tiered_enabled')),
                ]),

            Section::make('Conditional Rules')
                ->icon('heroicon-m-funnel')
                ->description('Configure conditional discount rules based on cart properties.')
                ->collapsible()
                ->schema([
                    Forms\Components\Repeater::make('conditional_rules')
                        ->label('Conditional Discount Rules')
                        ->helperText('Define conditions that trigger discounts.')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('condition')
                                    ->label('Condition Type')
                                    ->options([
                                        'cart_total' => 'Cart Total',
                                        'item_count' => 'Item Count',
                                        'specific_product' => 'Specific Product in Cart',
                                        'specific_category' => 'Specific Category in Cart',
                                    ])
                                    ->required(),

                                Select::make('operator')
                                    ->label('Operator')
                                    ->options([
                                        '>' => 'Greater Than',
                                        '>=' => 'Greater Than or Equal',
                                        '<' => 'Less Than',
                                        '<=' => 'Less Than or Equal',
                                        '=' => 'Equal',
                                        '!=' => 'Not Equal',
                                        'has' => 'Has',
                                        'not_has' => 'Does Not Have',
                                    ])
                                    ->required(),

                                TextInput::make('value')
                                    ->label('Value')
                                    ->helperText('Threshold value or product/category IDs (comma-separated).')
                                    ->required(),
                            ]),

                            Grid::make(3)->schema([
                                Select::make('discount_type')
                                    ->label('Discount Type')
                                    ->options([
                                        'percentage' => 'Percentage',
                                        'fixed' => 'Fixed Amount',
                                    ])
                                    ->default('percentage')
                                    ->required(),

                                TextInput::make('discount_value')
                                    ->label('Discount Value')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),

                                TextInput::make('max_discount')
                                    ->label('Maximum Discount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('No limit')
                                    ->visible(fn($get) => $get('discount_type') === 'percentage'),
                            ]),
                        ])
                        ->addable()
                        ->deletable()
                        ->reorderable(),
                ]),

            Section::make('Advanced Rules')
                ->icon('heroicon-m-cog-6-tooth')
                ->description('Configure additional restrictions and rules.')
                ->collapsible()
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('is_stackable')
                            ->label('Can Stack')
                            ->helperText('Allow this coupon to be combined with other coupons.')
                            ->default(false),

                        Toggle::make('first_time_only')
                            ->label('First-Time Customers Only')
                            ->helperText('Only allow for customers with no previous orders.')
                            ->default(false),

                        Toggle::make('auto_apply')
                            ->label('Auto-Apply')
                            ->helperText('Automatically apply when conditions are met.')
                            ->default(false),

                        Toggle::make('discount_shipping')
                            ->label('Discount Shipping')
                            ->helperText('Apply discount to shipping cost as well.')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),

                    Forms\Components\Textarea::make('product_ids')
                        ->label('Product Restrictions')
                        ->helperText('Comma-separated product IDs. Leave empty to apply to all products.')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('excluded_product_ids')
                        ->label('Excluded Products')
                        ->helperText('Comma-separated product IDs to exclude from this coupon.')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('category_ids')
                        ->label('Category Restrictions')
                        ->helperText('Comma-separated category IDs. Leave empty to apply to all categories.')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('customer_group_ids')
                        ->label('Customer Group Restrictions')
                        ->helperText('Comma-separated customer group IDs. Leave empty for all groups.')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // task-2026-05-26 / AI-1088 — exclude Faker-generated coupon rows.
            // Factory produces 3 lowercase Latin words as coupon_name; filter
            // at SQL level via REGEXP (all-lowercase multi-word, no digits/caps).
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->whereRaw("coupon_name NOT REGEXP '^[a-z]+ [a-z]+( [a-z]+)*$'")
            )
            ->columns([
                TextColumn::make('coupon_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('coupon_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-clipboard-document'),

                TextColumn::make('formatted_discount')
                    ->label('Discount')
                    ->state(fn ($record) => $record->formatted_discount),

                TextColumn::make('discount_type')
                    ->label('Type')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('usage_stats')
                    ->label('Usage')
                    ->state(function ($record) {
                        $stats = $record->getUsageStats();
                        $used = $stats['total_uses'] ?? 0;
                        $limit = $record->uses_per_coupon;
                        return $limit > 0 ? "{$used}/{$limit}" : (string) $used;
                    }),

                TextColumn::make('total_amount')
                    ->label('Min. Order')
                    ->formatStateUsing(fn($state): string => $state ? price_format($state) : '-'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_stackable')
                    ->label('Stackable')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle'),

                IconColumn::make('auto_apply')
                    ->label('Auto-Apply')
                    ->boolean()
                    ->trueIcon('heroicon-m-bolt')
                    ->falseIcon('heroicon-m-x-circle'),

                TextColumn::make('valid_from')
                    ->label('Valid From')
                    ->dateTime('M d, Y')
                    ->toggleable(isToggledHiddenByDefault: true),

TextColumn::make('valid_to')
                ->label('Valid Until')
                ->dateTime('M d, Y')
                ->toggleable(isToggledHiddenByDefault: true),

            IconColumn::make('bogo_enabled')
                ->label('BOGO')
                ->boolean()
                ->trueIcon('heroicon-m-gift')
                ->falseIcon('heroicon-m-x-circle')
                ->toggleable(isToggledHiddenByDefault: true),

            IconColumn::make('tiered_enabled')
                ->label('Tiered')
                ->boolean()
                ->trueIcon('heroicon-m-chart-bar')
                ->falseIcon('heroicon-m-x-circle')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed_amount' => 'Fixed Amount',
                        'free_shipping' => 'Free Shipping',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active'),
                TernaryFilter::make('is_stackable')
                    ->label('Stackable'),
                TernaryFilter::make('first_time_only')
                    ->label('First-Time Only'),
                TernaryFilter::make('auto_apply')
                    ->label('Auto-Apply'),
                Tables\Filters\Filter::make('valid_date')
                    ->form([
                        Forms\Components\DatePicker::make('valid_from')
                            ->label('Valid From'),
                        Forms\Components\DatePicker::make('valid_to')
                            ->label('Valid Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['valid_from'], fn (Builder $query, $date): Builder =>
                                $query->whereDate('valid_from', '>=', $date))
                            ->when($data['valid_to'], fn (Builder $query, $date): Builder =>
                                $query->whereDate('valid_to', '<=', $date));
                    }),
            ])
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\ViewAction::make(),
                    EditAction::make(),
                    \Filament\Actions\Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-m-document-duplicate')
                        ->action(function (Coupon $record) {
                            $newCoupon = $record->replicate();
                            $newCoupon->coupon_code = $record->coupon_code . '-COPY';
                            $newCoupon->times_used = 0;
                            $newCoupon->total_discount_given = 0;
                            $newCoupon->save();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Duplicate Coupon')
                        ->modalDescription('Are you sure you want to duplicate this coupon? A new code will be generated.'),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                \Filament\Actions\BulkAction::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-m-check-circle')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->update(['is_active' => true]);
                        }
                    }),
                \Filament\Actions\BulkAction::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-m-x-circle')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->update(['is_active' => false]);
                        }
                    }),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
