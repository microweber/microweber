<?php

namespace Modules\Product\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\Product\Models\ProductPricingRule;

class ProductPricingRuleResource extends Resource
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 10;

    protected static ?string $label = 'Pricing Rule';

    protected static ?string $pluralLabel = 'Pricing Rules';

    public static function getModel(): string
    {
        return ProductPricingRule::class;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(static::formSchema());
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, \Filament\Schemas\Components\Utilities\Set $set, $livewire) {
                            if (empty($livewire->data['slug'])) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Unique identifier for this rule'),

                    Textarea::make('description')
                        ->maxLength(500)
                        ->rows(2),

                    Select::make('rule_type')
                        ->required()
                        ->options([
                            ProductPricingRule::RULE_TYPE_BULK_QUANTITY => 'Bulk Quantity Discount',
                            ProductPricingRule::RULE_TYPE_BULK_AMOUNT => 'Bulk Amount Discount',
                            ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC => 'Customer Specific Pricing',
                            ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP => 'Customer Group Pricing',
                            ProductPricingRule::RULE_TYPE_VARIANT_OVERRIDE => 'Variant Override',
                            ProductPricingRule::RULE_TYPE_BUNDLE_DISCOUNT => 'Bundle Discount',
                        ])
                        ->default(ProductPricingRule::RULE_TYPE_BULK_QUANTITY),

                    Select::make('price_type')
                        ->required()
                        ->options([
                            ProductPricingRule::PRICE_TYPE_FIXED => 'Fixed Price',
                            ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT => 'Percentage Discount',
                            ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT => 'Fixed Amount Discount',
                        ])
                        ->default(ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT)
                        ->helperText('How the discount will be calculated'),

                    TextInput::make('priority')
                        ->numeric()
                        ->default(0)
                        ->helperText('Higher priority rules are applied first'),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    Toggle::make('is_stackable')
                        ->label('Stackable')
                        ->default(false)
                        ->helperText('Can this rule stack with other pricing rules?'),
                ])
                ->columns(2),

            Section::make('Product & Category Scope')
                ->schema([
                    KeyValue::make('product_ids')
                        ->label('Product IDs')
                        ->keyLabel('ID')
                        ->valueLabel('Product')
                        ->helperText('Leave empty to apply to all products. Use "all" key to apply to all products.'),

                    KeyValue::make('excluded_product_ids')
                        ->label('Excluded Product IDs')
                        ->keyLabel('ID')
                        ->valueLabel('Product'),

                    KeyValue::make('category_ids')
                        ->label('Category IDs')
                        ->keyLabel('ID')
                        ->valueLabel('Category')
                        ->helperText('Leave empty to apply to all categories'),

                    KeyValue::make('excluded_category_ids')
                        ->label('Excluded Category IDs')
                        ->keyLabel('ID')
                        ->valueLabel('Category'),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Customer Scope')
                ->schema([
                    Toggle::make('is_public')
                        ->label('Public')
                        ->default(true)
                        ->helperText('Available to all customers'),

                    KeyValue::make('customer_group_ids')
                        ->label('Customer Group IDs')
                        ->keyLabel('Group ID')
                        ->valueLabel('Group')
                        ->helperText('Leave empty for all groups'),

                    KeyValue::make('customer_ids')
                        ->label('Specific Customer IDs')
                        ->keyLabel('Customer ID')
                        ->valueLabel('Customer')
                        ->helperText('For customer-specific pricing'),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Pricing Tiers')
                ->schema([
                    Repeater::make('tiers')
                        ->label('Pricing Tiers')
                        ->helperText('Define quantity or amount thresholds with corresponding discounts')
                        ->schema([
                            TextInput::make('min')
                                ->label('Minimum')
                                ->numeric()
                                ->required()
                                ->helperText('Minimum quantity or amount'),

                            TextInput::make('max')
                                ->label('Maximum')
                                ->numeric()
                                ->helperText('Leave empty for unlimited'),

TextInput::make('value')
                                            ->label('Discount Value')
                                            ->numeric()
                                            ->required()
                                            ->helperText('Price or discount percentage/amount'),
                                    ])
                                        ->columns(3)
                                        ->collapsible()
                                        ->itemLabel(fn(array $state): ?string => ($state['min'] ?? '') . '-' . ($state['max'] ?? '∞'))
                                        ->addActionLabel('Add Tier')
                                        ->reorderable()
                                        ->defaultItems(0),
                ])
                ->collapsible(),

            Section::make('Usage Limits')
                ->schema([
                    TextInput::make('max_usage_count')
                        ->label('Maximum Total Uses')
                        ->numeric()
                        ->nullable()
                        ->helperText('Leave empty for unlimited'),

                    TextInput::make('max_usage_per_customer')
                        ->label('Maximum Uses Per Customer')
                        ->numeric()
                        ->nullable()
                        ->helperText('Leave empty for unlimited'),

                    TextInput::make('usage_count')
                        ->label('Current Usage Count')
                        ->numeric()
                        ->disabled()
                        ->default(0),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Active Period')
                ->schema([
                    DatePicker::make('valid_from')
                        ->label('Valid From')
                        ->nullable(),

                    DatePicker::make('valid_to')
                        ->label('Valid To')
                        ->nullable()
                        ->afterOrEqual('valid_from'),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Metadata')
                ->schema([
                    KeyValue::make('metadata')
                        ->label('Additional Metadata')
                        ->keyLabel('Key')
                        ->valueLabel('Value'),

                    KeyValue::make('cannot_stack_with')
                        ->label('Cannot Stack With Rules')
                        ->keyLabel('Rule ID')
                        ->valueLabel('Rule'),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('rule_type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        ProductPricingRule::RULE_TYPE_BULK_QUANTITY => 'Bulk Qty',
                        ProductPricingRule::RULE_TYPE_BULK_AMOUNT => 'Bulk Amount',
                        ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC => 'Customer Specific',
                        ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP => 'Customer Group',
                        ProductPricingRule::RULE_TYPE_VARIANT_OVERRIDE => 'Variant Override',
                        ProductPricingRule::RULE_TYPE_BUNDLE_DISCOUNT => 'Bundle Discount',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        ProductPricingRule::RULE_TYPE_BULK_QUANTITY => 'primary',
                        ProductPricingRule::RULE_TYPE_BULK_AMOUNT => 'info',
                        ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC => 'success',
                        ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP => 'warning',
                        ProductPricingRule::RULE_TYPE_VARIANT_OVERRIDE => 'danger',
                        ProductPricingRule::RULE_TYPE_BUNDLE_DISCOUNT => 'purple',
                        default => 'gray',
                    }),

                TextColumn::make('price_type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        ProductPricingRule::PRICE_TYPE_FIXED => 'Fixed Price',
                        ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT => '% Discount',
                        ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT => 'Fixed Discount',
                        default => $state,
                    }),

                TextColumn::make('tier_count')
                    ->label('Tiers')
                    ->state(fn(ProductPricingRule $record): int => is_array($record->tiers) ? count($record->tiers) : 0),

                TextColumn::make('valid_from')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('valid_to')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_stackable')
                    ->label('Stackable')
                    ->boolean(),

                TextColumn::make('usage_count')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('rule_type')
                    ->options([
                        ProductPricingRule::RULE_TYPE_BULK_QUANTITY => 'Bulk Quantity',
                        ProductPricingRule::RULE_TYPE_BULK_AMOUNT => 'Bulk Amount',
                        ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC => 'Customer Specific',
                        ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP => 'Customer Group',
                        ProductPricingRule::RULE_TYPE_VARIANT_OVERRIDE => 'Variant Override',
                        ProductPricingRule::RULE_TYPE_BUNDLE_DISCOUNT => 'Bundle Discount',
                    ]),

                SelectFilter::make('price_type')
                    ->options([
                        ProductPricingRule::PRICE_TYPE_FIXED => 'Fixed Price',
                        ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT => 'Percentage Discount',
                        ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT => 'Fixed Discount',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active'),

                TernaryFilter::make('is_stackable')
                    ->label('Stackable'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('priority', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ProductPricingRuleResource\Pages\ListProductPricingRules::route('/'),
            'create' => ProductPricingRuleResource\Pages\CreateProductPricingRule::route('/create'),
            'edit' => ProductPricingRuleResource\Pages\EditProductPricingRule::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }
}
