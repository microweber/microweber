<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class AdminShopInvoicesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-invoices';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

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


                        TextInput::make('options.shop.invoice_company_name')
                            ->label('Company Name')
                            ->placeholder('Enter your company name')
                            ->live(),


                        Select::make('options.shop.invoice_company_country')
                            ->label('Company Country')
                            ->live()
                            ->options([
                                '' => 'Select country',
                                'US' => 'United States',
                                'CA' => 'Canada',
                                'GB' => 'United Kingdom',
                                'AU' => 'Australia',
                                'DE' => 'Germany',
                                'NL' => 'Netherlands',
                                'SE' => 'Sweden',
                                'NO' => 'Norway',
                                'DK' => 'Denmark',
                                'FI' => 'Finland',
                                'IE' => 'Ireland',
                                'CH' => 'Switzerland',
                                'AT' => 'Austria',
                                'BE' => 'Belgium',
                                'LU' => 'Luxembourg',
                                'FR' => 'France',
                                'IT' => 'Italy',
                                'ES' => 'Spain',
                                'PT' => 'Portugal',
                                'GR' => 'Greece',
                                'CZ' => 'Czech Republic',
                                'PL' => 'Poland',
                                'HU' => 'Hungary',
                                'RO' => 'Romania',
                                'BG' => 'Bulgaria',
                                'HR' => 'Croatia',
                                'RS' => 'Serbia',
                                'SI' => 'Slovenia',
                                'SK' => 'Slovakia',
                                'LT' => 'Lithuania',
                                'LV' => 'Latvia',
                                'EE' => 'Estonia',
                                'MT' => 'Malta',
                                'CY' => 'Cyprus',
                            ]),


                        TextInput::make('options.shop.invoice_company_city')
                            ->label('Company City')
                            ->placeholder('Enter your company name')
                            ->live(),

                        TextInput::make('options.shop.invoice_company_address')
                            ->label('Company Address')
                            ->placeholder('Enter your company address')
                            ->live(),

                        TextInput::make('options.shop.invoice_company_vat_number')
                            ->label('Company VAT Number')
                            ->placeholder('Enter your company vat number')
                            ->live(),

                        TextInput::make('options.shop.invoice_id_company_number')
                            ->label('ID Company Number')
                            ->placeholder('Enter your ID company number')
                            ->live(),

                        Textarea::make('options.shop.invoice_company_bank_details')
                            ->label('Additional information')
                            ->live()
                            ->rows(5)
                            ->cols(5)
                            ->placeholder('For example: reason for taxes'),


                        Textarea::make('options.shop.invoice_company_bank_details')
                            ->label('Bank transfer details')
                            ->live()
                            ->rows(5)
                            ->cols(5)
                            ->placeholder('For example: reason for taxes'),

                    ]),
            ]);

    }

}
