<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class AdminShopInvoicesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-invoices';

    protected static string $view = 'filament.admin.pages.settings-shop-invoices';

    protected static ?string $title = 'Invoices';

    protected static string $description = 'Configure your shop invoices settings';

    protected static ?string $navigationGroup = 'Shop Settings';


    public array $optionGroups = [
        'shop'
    ];


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Invoices')
                    ->view('filament-forms::sections.section')
                    ->description('Configure your shop invoices settings.')
                    ->schema([
                        Checkbox::make('options.shop.enable_invoices')
                            ->label('Enable invoicing')
                            ->live(),


                        MwFileUpload::make('options.shop.invoice_company_logo')
                            ->label('Company Logo')
                            ->helperText('Select an Company Logo for your website.')
                            ->live(),



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
            ]);

    }

}
