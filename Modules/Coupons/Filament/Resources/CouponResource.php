<?php

namespace Modules\Coupons\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Modules\Coupons\Models\Coupon;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Coupons\Filament\Resources\CouponResource\Pages;
use Modules\Coupons\Filament\Resources\CouponResource\RelationManagers;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 12;
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('coupon_name')
                        ->label('Coupon Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('coupon_code')
                        ->label('Coupon Code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Select::make('discount_type')
                        ->label('Discount Type')
                        ->live()
                        ->reactive()
                        ->options([
                            'percentage' => 'Percentage',
                            'fixed_amount' => 'Fixed Amount'
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('discount_value')
                        ->label('Discount Value')
                        ->required()
                        ->live()
                        ->reactive()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(fn(callable $get) => $get('discount_type') === 'percentage' ? 100 : null
                        ),

                    Forms\Components\TextInput::make('total_amount')
                        ->label('Minimum Order Amount')
                        ->numeric()
                        ->minValue(0),

                    Forms\Components\TextInput::make('uses_per_coupon')
                        ->label('Uses Per Coupon')
                        ->numeric()
                        ->minValue(0),

                    Forms\Components\TextInput::make('uses_per_customer')
                        ->label('Uses Per Customer')
                        ->numeric()
                        ->minValue(0),

                    Forms\Components\Toggle::make('is_active')
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
                Tables\Columns\TextColumn::make('coupon_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('coupon_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Type')
                    ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state))
                    ),

                Tables\Columns\TextColumn::make('discount_value')
                    ->label('Value')
                    ->formatStateUsing(fn($state, $record): string => $record->discount_type === 'percentage'
                        ? "{$state}%"
                        : price_format($state)
                    ),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Min. Amount')
                    ->formatStateUsing(fn($state): string => $state ? price_format($state) : '-'
                    ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('discount_type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed_amount' => 'Fixed Amount',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
