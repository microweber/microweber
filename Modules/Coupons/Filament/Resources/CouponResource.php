<?php

namespace Modules\Coupons\Filament\Resources;

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
use Modules\Coupons\Filament\Resources\CouponResource\Pages;
use Modules\Coupons\Filament\Resources\CouponResource\RelationManagers;
use Modules\Coupons\Models\Coupon;

class CouponResource extends Resource
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $model = Coupon::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static ?int $navigationSort = 12;

    protected static string $description = 'Configure your shop coupons settings';

    public static function getDescription(): string
    {
        return static::$description;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()->schema([
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

            Select::make('discount_type')
                ->label('Discount Type')
                ->live()
                ->options([
                                'percentage' => 'Percentage',
                                'fixed_amount' => 'Fixed Amount'
                            ])
                            ->required(),

            TextInput::make('discount_value')
                ->label('Discount Value')
                ->required()
                ->live()
                ->numeric()
                            ->minValue(0)
                            ->maxValue(fn(callable $get) => $get('discount_type') === 'percentage' ? 100 : null
                            ),

                        TextInput::make('total_amount')
                            ->label('Minimum Order Amount')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('uses_per_coupon')
                            ->label('Uses Per Coupon')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('uses_per_customer')
                            ->label('Uses Per Customer')
                            ->numeric()
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('coupon_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('coupon_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('discount_type')
                    ->label('Type')
                    ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state))
                    ),

                TextColumn::make('discount_value')
                    ->label('Value')
                    ->formatStateUsing(fn($state, $record): string => $record->discount_type === 'percentage'
                        ? "{$state}%"
                        : price_format($state)
                    ),

                TextColumn::make('total_amount')
                    ->label('Min. Amount')
                    ->formatStateUsing(fn($state): string => $state ? price_format($state) : '-'
                    ),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed_amount' => 'Fixed Amount',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active'),
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
