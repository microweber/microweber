<?php

namespace MicroweberPackages\Payment\Filament\Admin\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Payment\Models\PaymentProvider;

class PaymentProviderResource extends Resource
{
    protected static ?string $model = PaymentProvider::class;

   // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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
            'index' => \MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders::route('/'),
            'create' => \MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider::route('/create'),
            'edit' => \MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\EditPaymentProvider::route('/{record}/edit'),
        ];
    }
}
