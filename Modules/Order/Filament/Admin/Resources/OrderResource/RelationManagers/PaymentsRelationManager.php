<?php

namespace Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers;


use Akaunting\Money\Currency;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Models\PaymentProvider;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'transaction_id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                    ->required(),

                Forms\Components\Select::make('currency')
                    ->options(collect(Currency::getCurrencies())->mapWithKeys(fn ($item, $key) => [$key => data_get($item, 'name')]))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->columnSpanFull()
                    ->default(PaymentStatus::Pending)
                    ->options(PaymentStatus::class)
                    ->required(),

                Forms\Components\ToggleButtons::make('payment_provider_id')
                    ->label('Payment Provider')
                    ->inline()
                    ->grouped()
                    ->columnSpanFull()
                    ->options(PaymentProvider::all()->pluck('name', 'id')->toArray())
                    ->required(),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->sortable()
                    ->money(fn ($record) => $record->currency),

                Tables\Columns\TextColumn::make('paymentProvider.name')
//                   ->formatStateUsing(function ($state) {
//                       dd($state);
//                       dd($record->paymentProvider->name);
//                        return $record->paymentProvider->name;
//                    })
                    //->format(fn ($record) => $record->paymentProvider()->name)
                 //   ->formatStateUsing(fn ($state) => Str::headline($state))
                   // ->formatStateUsing(fn ($record) => $record->paymentProvider()->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
               // paymentProviderName
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
