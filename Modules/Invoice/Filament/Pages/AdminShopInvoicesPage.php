<?php

namespace Modules\Invoice\Filament\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Invoice\Filament\Resources\InvoiceResource;

class AdminShopInvoicesPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Invoices';

    protected static string $description = 'Configure your shop invoices settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';

    protected static bool $shouldRegisterNavigation = true;


    public array $optionGroups = [
        'shop'
    ];
    protected function getHeaderActions(): array
    {
        return [
            Action::make('InvoiceResourceList')
                ->label('Invoices List')
                ->url(InvoiceResource::getUrl())
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Invoices')
                    ->view('mw-filament::sections.section')
                    ->description('Configure your shop invoices settings.')
                    ->schema([
                        // Stored as '1'/'n' (not a bool): Microweber's option store
                        // drops falsy values, so an unchecked checkbox could never
                        // persist a disabled state. A select keeps both states.
                        Select::make('options.shop.enable_invoices')
                            ->label('Enable invoicing')
                            ->options([
                                '1' => 'Enabled',
                                'n' => 'Disabled',
                            ])
                            ->default('1')
                            ->selectablePlaceholder(false)
                            ->live(),

                        // Opt-in: create a draft invoice automatically when a new
                        // order is placed. Only relevant while invoicing is on.
                        Select::make('options.shop.auto_generate_invoices')
                            ->label('Auto-generate invoices for new orders')
                            ->helperText('When enabled, a draft invoice is created automatically whenever a new order is placed.')
                            ->options([
                                '1' => 'Enabled',
                                'n' => 'Disabled',
                            ])
                            ->default('n')
                            ->selectablePlaceholder(false)
                            ->visible(fn (callable $get): bool => in_array($get('options.shop.enable_invoices'), ['1', 'y', true, 1], true))
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

                        Textarea::make('options.shop.invoice_company_additional_info')
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
