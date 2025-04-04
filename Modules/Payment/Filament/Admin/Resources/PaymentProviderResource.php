<?php

namespace Modules\Payment\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use Modules\Payment\Models\PaymentProvider;

class PaymentProviderResource extends Resource
{
    protected static ?string $model = PaymentProvider::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop Settings';
    protected static ?int $navigationSort = 4;


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
        $paymentDrivers = app()->payment_method_manager->getDrivers();
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
            'paymentProviders' => $paymentProviders,
            'paymentDrivers' => $paymentDrivers
        ];
    }

    public static function form(Form $form): Form
    {
        $getAvailableToSetup = self::getAvailableToSetup();
        $paymentDrivers = $getAvailableToSetup['paymentDrivers'];
        $paymentProviders = $getAvailableToSetup['paymentProviders'];

        return $form->schema([
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Select Provider')
                    ->visible(function (Forms\Get $get) {
                        return !$get('id');

                    })
                    ->schema([
                        RadioDeck::make('provider')
                            ->live()
                            ->required()
                            ->padding('py-4 px-8')
                            ->gap('gap-0')
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, string $state) use ($paymentProviders) {
                                if ($state) {

                                    if(!$get('name')){
                                        $set('name', $paymentProviders[$state] ?? null);
                                    }
                                    if(!$get('is_active')) {
                                        $set('is_active', 1);
                                    }
                                    $set('settings', []);
                                }
                            })
                            ->extraCardsAttributes(['class' => 'rounded-xl'])
                            ->extraOptionsAttributes(['class' => 'text-lg leading-none w-full flex flex-col p-4'])
                            ->extraDescriptionsAttributes(['class' => 'text-sm font-light'])
                            ->iconSize(IconSize::Large)
                            ->color('primary')
                            ->options($paymentProviders)
                            ->columnSpanFull()
                            ->label('Select Provider'),
                    ]),
                Forms\Components\Wizard\Step::make('Provider Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Name')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(1)
                            ->label('Is Active')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Wizard\Step::make('Settings')
                    ->schema(function (Forms\Get $get) use ($form) {

                        $paymentDriver = $get('provider');

                        if (!$paymentDriver) {
                            return [];
                        }

                        $schemas = [];
                        $driver = app()->payment_method_manager->driver($paymentDriver);

                        /* @var \Modules\Payment\Drivers\AbstractPaymentMethod $driver */

                        if (is_object($driver) && method_exists($driver, 'getSettingsForm')) {
                            $providerForm = $driver->getSettingsForm();
                            if ($providerForm) {
                                foreach ($providerForm as $component) {
                                    $component->columnSpanFull();
                                }
                                $schemas = array_merge($schemas, $providerForm);
                            }
                        }

//
//                        foreach ($paymentDrivers as $paymentDriver) {
//
//                        }


                        return $schemas;
                    }),
            ])
                ->skippable()
                ->columnSpanFull(),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })
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
        $pages = [];
        $pages['index'] = \Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders::route('/');
        $pages['create'] = \Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider::route('/create');
        $pages['edit'] = \Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\EditPaymentProvider::route('/{record}/edit');

        return $pages;
    }
}
