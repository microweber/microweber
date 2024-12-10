<?php

namespace Modules\Payment\Filament\Admin\Resources;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\CreatePayment;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\EditPayment;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\ListPayments;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;


    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rel_id')
                    ->label('Related ID')
                    ->required(),
                TextInput::make('rel_type')
                    ->label('Related Type')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix(fn($record) => $record?->currency ?? ''),
                TextInput::make('currency')
                    ->required(),
                Select::make('status')
                    ->options(PaymentStatus::class)
                    ->required(),
                Select::make('payment_provider')
                    ->options(fn() => PaymentProvider::where('is_active', 1)->get()->pluck('name', 'provider')->toArray())
                    ->required()
                    ->searchable(),
                TextInput::make('transaction_id'),
                KeyValue::make('payment_data')
                    ->label('Payment Data'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([10, 25, 50, 100, 200, 500, 1000, 2000, 'all'])
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rel_type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('rel_id')
                    ->label('Related ID')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money(fn($record) => $record->currency)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'failed' => 'danger',
                        'pending' => 'warning',
                        'completed' => 'success',
                        default => 'secondary',
                    }),
                TextColumn::make('payment_provider')
                    ->label('Provider')
                    ->searchable(),
                TextColumn::make('transaction_id')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PaymentStatus::class),
                Tables\Filters\SelectFilter::make('payment_provider'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        $pages = [];
        $pages['index'] = ListPayments::route('/');
        $pages['create'] = CreatePayment::route('/create');
        $pages['edit'] = EditPayment::route('/{record}/edit');

        return $pages;
    }
}
