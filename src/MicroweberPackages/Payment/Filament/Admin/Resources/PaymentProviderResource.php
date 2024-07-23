<?php

namespace MicroweberPackages\Payment\Filament\Admin\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Payment\Models\PaymentProvider;

use Filament\Forms;

class PaymentProviderResource extends Resource
{
    protected static ?string $model = PaymentProvider::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';


    public static function form(Form $form): Form
    {

        $paymentDrivers = app()->payment_method_manager->getProviders();


        $schema = [

            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->placeholder('Name')
                ->required()
                ->columnSpan('full'),

            Forms\Components\Select::make('provider')
                ->label('Provider')
                ->live()
                ->reactive()
                ->afterStateUpdated(function (Forms\Components\Select $component, Forms\Set $set, Forms\Get $get, ?string $state) {
                    $set('provider', $state);
                })
                ->placeholder('Select Provider')
                ->options(function () use ($paymentDrivers) {
                    if ($paymentDrivers) {
                        $options = [];
                        foreach ($paymentDrivers as $paymentDriver) {
                            $driver = app()->payment_method_manager->driver($paymentDriver);
                            $options[$paymentDriver] = $driver->title();
                        }
                        return $options;
                    }
                })
//                ->options([
//                    'pay_on_delivery' => 'Pay on Delivery',
//                    'paypal' => 'Paypal',
//
//                ])
                ->required()
                ->columnSpan('full'),

            Forms\Components\Toggle::make('is_active')
                ->default(1)
                ->label('Is Active')
                ->columnSpan('full')
                ->required(),
        ];

        //  $provderForm = static::getProviderSettingsForm($form);
        //   $schema = array_merge($schema, $provderForm);


        if ($paymentDrivers) {
            foreach ($paymentDrivers as $paymentDriver) {
                $driver = app()->payment_method_manager->driver($paymentDriver);
                if (method_exists($driver, 'getSettingsForm')) {
                    $provderForm = $driver->getSettingsForm($form);
                    if ($provderForm) {

                        $schema = array_merge($schema, $provderForm);
                    }
                }
            }
        }


        return $form
            ->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Is Active')
                    ->sortable(),
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
