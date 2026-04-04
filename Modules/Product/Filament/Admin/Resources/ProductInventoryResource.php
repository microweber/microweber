<?php

namespace Modules\Product\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Content\Models\Content;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages;
use Modules\Product\Models\ProductInventoryMovement;
use Modules\Product\Services\InventoryService;

class ProductInventoryResource extends Resource
{
    protected static string | \BackedEnum | null $navigationIcon = null;

    protected static string | \UnitEnum | null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 10;

    protected static ?string $model = ProductInventoryMovement::class;
    protected static ?string $recordTitleAttribute = 'notes';

    protected static ?string $label = 'Inventory Movement';

    protected static ?string $pluralLabel = 'Inventory';

    protected static ?string $navigationLabel = 'Inventory';

    public static function getGloballySearchableAttributes(): array
    {
        return ['notes', 'type', 'product.title'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->product?->title ?? 'Inventory #' . $record->id;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->type) {
            $details['Type'] = ucfirst($record->type);
        }

        if ($record->quantity_change) {
            $details['Qty Change'] = ($record->quantity_change > 0 ? '+' : '') . $record->quantity_change;
        }

        return $details;
    }

    protected static ?string $slug = 'inventory';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Movement Details')
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->options(function () {
                            return Content::where('content_type', 'product')
                                ->pluck('title', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->required(),

                    Select::make('variant_id')
                        ->label('Variant')
                        ->relationship('variant', 'sku')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Select::make('type')
                        ->label('Movement Type')
                        ->options([
                            ProductInventoryMovement::TYPE_SALE => 'Sale',
                            ProductInventoryMovement::TYPE_RESTOCK => 'Restock',
                            ProductInventoryMovement::TYPE_ADJUSTMENT => 'Adjustment',
                            ProductInventoryMovement::TYPE_RETURN => 'Return',
                            ProductInventoryMovement::TYPE_DAMAGED => 'Damaged',
                            ProductInventoryMovement::TYPE_LOST => 'Lost',
                            ProductInventoryMovement::TYPE_TRANSFER_IN => 'Transfer In',
                            ProductInventoryMovement::TYPE_TRANSFER_OUT => 'Transfer Out',
                            ProductInventoryMovement::TYPE_INITIAL => 'Initial Stock',
                        ])
                        ->required(),

                    TextInput::make('quantity_change')
                        ->label('Quantity Change')
                        ->numeric()
                        ->required()
                        ->helperText('Positive for restock, negative for sale'),

                    TextInput::make('quantity_before')
                        ->label('Quantity Before')
                        ->numeric()
                        ->disabled(),

                    TextInput::make('quantity_after')
                        ->label('Quantity After')
                        ->numeric()
                        ->disabled(),
                ])
                ->columns(2),

            Section::make('Reference Information')
                ->schema([
                    TextInput::make('reference_type')
                        ->label('Reference Type')
                        ->placeholder('e.g., order, cart'),

                    TextInput::make('reference_id')
                        ->label('Reference ID')
                        ->numeric(),

                    Textarea::make('notes')
                        ->label('Notes')
                        ->rows(3),

                    DateTimePicker::make('created_at')
                        ->label('Created At')
                        ->disabled(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('variant.sku')
                    ->label('Variant SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'danger' => ProductInventoryMovement::TYPE_SALE,
                        'success' => ProductInventoryMovement::TYPE_RESTOCK,
                        'warning' => ProductInventoryMovement::TYPE_ADJUSTMENT,
                        'info' => ProductInventoryMovement::TYPE_RETURN,
                        'gray' => [
                            ProductInventoryMovement::TYPE_DAMAGED,
                            ProductInventoryMovement::TYPE_LOST,
                            ProductInventoryMovement::TYPE_INITIAL,
                        ],
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            ProductInventoryMovement::TYPE_SALE => 'Sale',
                            ProductInventoryMovement::TYPE_RESTOCK => 'Restock',
                            ProductInventoryMovement::TYPE_ADJUSTMENT => 'Adjustment',
                            ProductInventoryMovement::TYPE_RETURN => 'Return',
                            ProductInventoryMovement::TYPE_DAMAGED => 'Damaged',
                            ProductInventoryMovement::TYPE_LOST => 'Lost',
                            ProductInventoryMovement::TYPE_TRANSFER_IN => 'Transfer In',
                            ProductInventoryMovement::TYPE_TRANSFER_OUT => 'Transfer Out',
                            ProductInventoryMovement::TYPE_INITIAL => 'Initial',
                            default => $state,
                        };
                    })
                    ->sortable(),

                TextColumn::make('quantity_change')
                    ->label('Change')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $prefix = $state > 0 ? '+' : '';
                        return "{$prefix}{$state}";
                    })
                    ->color(function ($state) {
                        return $state > 0 ? 'success' : 'danger';
                    }),

                TextColumn::make('quantity_after')
                    ->label('Current Stock')
                    ->sortable(),

                TextColumn::make('reference_type')
                    ->label('Reference')
                    ->formatStateUsing(function ($record) {
                        if ($record->reference_type && $record->reference_id) {
                            return "{$record->reference_type} #{$record->reference_id}";
                        }
                        return '-';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Movement Type')
                    ->options([
                        ProductInventoryMovement::TYPE_SALE => 'Sale',
                        ProductInventoryMovement::TYPE_RESTOCK => 'Restock',
                        ProductInventoryMovement::TYPE_ADJUSTMENT => 'Adjustment',
                        ProductInventoryMovement::TYPE_RETURN => 'Return',
                        ProductInventoryMovement::TYPE_DAMAGED => 'Damaged',
                        ProductInventoryMovement::TYPE_LOST => 'Lost',
                        ProductInventoryMovement::TYPE_TRANSFER_IN => 'Transfer In',
                        ProductInventoryMovement::TYPE_TRANSFER_OUT => 'Transfer Out',
                        ProductInventoryMovement::TYPE_INITIAL => 'Initial Stock',
                    ]),

                TernaryFilter::make('is_positive')
                    ->label('Stock Change')
                    ->trueLabel('Stock Increased')
                    ->falseLabel('Stock Decreased')
                    ->queries(
                        true: fn (Builder $query) => $query->where('quantity_change', '>', 0),
                        false: fn (Builder $query) => $query->where('quantity_change', '<', 0),
                    ),

            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ProductInventoryResource\Pages\ListProductInventory::route('/'),
            'create' => ProductInventoryResource\Pages\CreateProductInventory::route('/create'),
            'edit' => ProductInventoryResource\Pages\EditProductInventory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        // Count unresolved low stock alerts
        $alertCount = \Modules\Product\Models\ProductInventoryAlert::unresolved()->count();
        return $alertCount > 0 ? (string) $alertCount : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }
}
