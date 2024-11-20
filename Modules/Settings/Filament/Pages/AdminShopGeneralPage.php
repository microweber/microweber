<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopGeneralPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-shop2';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'General';

    protected static string $description = 'Configure your shop general settings';

    protected static ?string $navigationGroup = 'Shop Settings';

    public array $optionGroups = [
        'payments'
    ];


    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Currency settings')
                    ->view('filament-forms::sections.section')
                    ->description('Fill in the fields for maximum results when finding your website in search engines.')
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
                            ])

                    ]),
            ]);
    }

}
