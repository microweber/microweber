<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use Modules\Coupons\Filament\Resources\CouponResource;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;

class AdminShopGeneralPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Main Shop Settings';

    protected static string $description = 'Configure your shop general settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';

    public array $optionGroups = [
        'website',
        'payments',
    ];


    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([


                Section::make('Currency settings')
                    ->view('mw-filament::sections.section')
                    ->description('Set the default currency and symbol for your shop.')
                    ->schema([

                        Select::make('options.payments.currency')
                            ->label('Set default currency')
                            ->live()
                            ->options([
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                                'GBP' => 'GBP',
                                'AUD' => 'AUD',
                                'CAD' => 'CAD',
                                'JPY' => 'JPY',
                                'CNY' => 'CNY',
                                'INR' => 'INR',
                                'RUB' => 'RUB',
                                'UAH' => 'UAH',
                                'PLN' => 'PLN',
                                'CHF' => 'CHF',
                                'SEK' => 'SEK',
                                'NOK' => 'NOK',
                                'DKK' => 'DKK',
                                'CZK' => 'CZK',
                                'HUF' => 'HUF',
                                'HRK' => 'HRK',
                                'BGN' => 'BGN',


                            ])
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">Default currency with which you will accept payments.</small>');
                            }),

                        Select::make('options.payments.currency_symbol_position')
                            ->label('Currency symbol position')
                            ->live()
                            ->options([
                                '' => 'Default',
                                'before' => 'Before amount',
                                'after' => 'After amount',
                            ])
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">Where to display the currency symbol before, after or by default relative to the amount.</small>');
                            }),

                        Select::make('options.payments.currency_symbol_decimal')
                            ->label('Show Decimals')
                            ->live()
                            ->options([
                                '' => 'Always',
                                'when_needed' => 'When needed',
                            ]),

                    ]),

                Section::make('Terms and conditions settings')
                    ->view('mw-filament::sections.section')
                    ->description('Configure TOS shop settings.')
                    ->schema([

                        Select::make('options.website.shop_require_terms')
                            ->label('Require Terms and Conditions')
                            ->helperText(function () {
                                return new HtmlString('<small class="mb-2 text-muted">If enabled, users must agree to the terms and conditions before completing a purchase.</small>');
                            })
                            ->live()
                            ->options([
                                '' => 'Not required',
                                '1' => 'Required',
                            ]),





                    ]),



                Section::make('Other settings')
                    ->view('mw-filament::sections.section')
                    ->description('Configure additional shop settings.')
                    ->schema([


                        Actions::make([

Actions\Action::make('payments_list')
                ->label('Payment transactions')
                ->url(PaymentResource::getUrl('index'))
                ->icon('heroicon-o-banknotes'),

Actions\Action::make('payment_provider_settings')
                ->label('Payment Settings')
                ->url(PaymentProviderResource::getUrl('index'))
                ->icon('heroicon-o-credit-card'),


Actions\Action::make('shipping_provider_settings')
                ->label('Shipping Settings')
                ->url(ShippingProviderResource::getUrl('index'))
                ->icon('heroicon-o-truck'),




Actions\Action::make('coupons')
                ->label('Coupons')
                ->url(CouponResource::getUrl('index'))
                ->icon('heroicon-o-ticket'),


Actions\Action::make('discount_prices')
                ->label('Discount Prices')
                ->url(OfferResource::getUrl('index'))
                ->icon('heroicon-o-tag'),


                        ]),


                    ]),


            ]);
    }

}
