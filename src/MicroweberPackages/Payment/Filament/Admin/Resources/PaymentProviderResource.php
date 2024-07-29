<?php

namespace MicroweberPackages\Payment\Filament\Admin\Resources;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Payment\Models\PaymentProvider;

use Filament\Forms;

class PaymentProviderResource extends Resource
{
    protected static ?string $model = PaymentProvider::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';


    public static function getAvailableToSetup()
    {
        $existingPaymentProvidersNames = [];
        $existingPaymentProviders = PaymentProvider::all();
        if ($existingPaymentProviders) {
            foreach ($existingPaymentProviders as $existingPaymentProvider) {
                $existingPaymentProvidersNames[] = $existingPaymentProvider->name;
            }
        }
        $paymentProviders = [];
        $paymentDrivers = app()->payment_method_manager->getProviders();
        if ($paymentDrivers) {
            foreach ($paymentDrivers as $paymentDriver) {
                $driver = app()->payment_method_manager->driver($paymentDriver);
                if (in_array($driver->title(), $existingPaymentProvidersNames)) {
                    continue;
                }

                $paymentProviders[$paymentDriver] = $driver->title();
            }
        }

        return [
            'paymentProviders'=>$paymentProviders,
            'paymentDrivers'=>$paymentDrivers
        ];
    }

    public static function form(Form $form): Form
    {

        $getAvailableToSetup = self::getAvailableToSetup();
        $paymentDrivers = $getAvailableToSetup['paymentDrivers'];
        $paymentProviders = $getAvailableToSetup['paymentProviders'];

        $schema = [

            RadioDeck::make('provider')
                ->live()
                ->hidden(function (?PaymentProvider $record) {
                    if ($record) {
                        return true;
                    }
                    return false;
                })
                ->required()
                ->padding('py-4 px-8')
                ->gap('gap-0')
                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, string $state) use($paymentProviders) {
                    $set('name', $paymentProviders[$state]);
                })
                ->extraCardsAttributes([ // Extra Attributes to add to the card HTML element
                    'class' => 'rounded-xl'
                ])
                ->extraOptionsAttributes([ // Extra Attributes to add to the option HTML element
                    'class' => 'text-lg leading-none w-full flex flex-col p-4'
                ])
                ->extraDescriptionsAttributes([ // Extra Attributes to add to the description HTML element
                    'class' => 'text-sm font-light'
                ])
                ->icons([
                    'all_subscribers' => 'heroicon-o-users',
                    'specific_list' => 'heroicon-o-list-bullet',
                    'import_new_list' => 'heroicon-o-arrow-up-tray',
                ])
                ->iconSize(IconSize::Large)
                ->color('primary')
                ->live()
                ->descriptions([

                ])
                ->columnSpanFull()
                ->columns(2)
                ->options($paymentProviders),
        ];

        $schema[] =  Forms\Components\Hidden::make('name')
            ->required()
            ->hidden(function (?PaymentProvider $record) {
                if ($record) {
                    return true;
                }
                return false;
            })
            ->columnSpan('full');

        $schema[] =  Forms\Components\TextInput::make('name')
            ->label('Name')
            ->placeholder('Name')
            ->required()
            ->hidden(function (?PaymentProvider $record) {
                if ($record) {
                    return false;
                }
                return true;
            })
            ->columnSpan('full');

        if ($paymentDrivers) {

            foreach ($paymentDrivers as $paymentDriver) {
                $driver = app()->payment_method_manager->driver($paymentDriver);
                if (is_object($driver) and method_exists($driver, 'getSettingsForm')) {
                    $provderForm = $driver->getSettingsForm($form);
                    if ($provderForm) {
                        $schema = array_merge($schema, $provderForm);
                    }
                }
            }
        }


        $schema[] = Forms\Components\Toggle::make('is_active')
            ->default(1)
            ->label('Is Active')
            ->columnSpan('full')
            ->hidden(function (?PaymentProvider $record) {
                if ($record) {
                    return false;
                }
                return true;
            })
            ->required();


        return $form
            ->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageUrlColumn::make('logo')
                    ->size(36)
                    ->extraImgAttributes([
                        'class' => '!object-contain',
                    ])
                    ->imageUrl(function (Model $record) {

                        return $record->logo();
                    }),


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
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
        $pages = [];
        $pages['index'] = \MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders::route('/');
        $pages['create'] = \MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider::route('/create');
        $pages['edit'] = \MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\EditPaymentProvider::route('/{record}/edit');

        return $pages;
    }
}
