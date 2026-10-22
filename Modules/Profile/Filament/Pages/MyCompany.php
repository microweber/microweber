<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Models\Company;
use Modules\Customer\Models\Customer;
use Modules\Profile\Filament\Concerns\InteractsWithCurrentCustomer;

/**
 * Lets a buyer edit their company details (name, VAT/registration number,
 * address, …) — the company that appears on their invoices. Stored on the
 * Company model and linked to the user's Customer (customer.company_id), so
 * invoice rendering ($customer->company) picks it up automatically.
 *
 * Lives in the profile panel under "Orders & Billing", next to Invoices.
 */
class MyCompany extends Page
{
    use InteractsWithCurrentCustomer;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 3;
    protected string $view = 'modules.profile::pages.my-company';

    public ?array $data = [];
    public ?Company $company = null;

    public function getTitle(): string
    {
        return __('Company');
    }

    public static function getNavigationLabel(): string
    {
        return __('Company');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Orders & Billing');
    }

    public function mount(): void
    {
        $this->company = $this->resolveCompany();

        $this->form->fill($this->company ? [
            'name' => $this->company->name,
            'company_number' => $this->company->company_number,
            'vat_number' => $this->company->vat_number,
            'email' => $this->company->email,
            'phone' => $this->company->phone,
            'website' => $this->company->website,
            'country' => $this->company->country,
            'city' => $this->company->city,
            'zip' => $this->company->zip,
            'address' => $this->company->address,
            'logo' => $this->company->logo,
            'description' => $this->company->description,
        ] : []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('Company details'))->columns(2)->schema([
                    TextInput::make('name')->label(__('Company name'))->required()->maxLength(191),
                    TextInput::make('company_number')->label(__('Registration number'))->maxLength(191),
                    TextInput::make('vat_number')->label(__('VAT number'))->maxLength(191),
                    TextInput::make('email')->label(__('Billing email'))->email(),
                    TextInput::make('phone')->label(__('Phone')),
                    TextInput::make('website')->label(__('Website'))->url(),
                ]),
                Section::make(__('Address'))->columns(2)->schema([
                    TextInput::make('address')->label(__('Address'))->columnSpanFull(),
                    TextInput::make('city')->label(__('City')),
                    TextInput::make('zip')->label(__('ZIP / Postcode')),
                    TextInput::make('country')->label(__('Country')),
                ]),
                Section::make(__('Branding'))->schema([
                    FileUpload::make('logo')->label(__('Logo'))->image()->disk('public')->directory('company-logos')->visibility('public'),
                    Textarea::make('description')->label(__('Notes'))->rows(3),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $customer = $this->currentCustomer();
        if (! $customer) {
            Notification::make()
                ->title(__('No customer record'))
                ->body(__('Place an order first to create your billing profile.'))
                ->warning()
                ->send();
            return;
        }

        $company = $this->company ?: new Company();
        $company->fill($data);
        $company->rel_type = Customer::class;
        $company->rel_id = $customer->id;
        if (! $company->exists) {
            $company->created_by = (int) Auth::id();
        }
        $company->save();

        // Link the company to the customer so invoices render it.
        if ((int) $customer->company_id !== (int) $company->id) {
            $customer->company_id = $company->id;
            $customer->save();
        }

        $this->company = $company;

        Notification::make()
            ->title(__('Saved'))
            ->body(__('Your company details have been updated.'))
            ->success()
            ->send();
    }

    protected function resolveCompany(): ?Company
    {
        $customer = $this->currentCustomer();
        if (! $customer) {
            return null;
        }
        if ($customer->company_id) {
            return Company::find($customer->company_id);
        }
        // Back-compat: a company previously attached by rel_type/rel_id.
        return Company::where('rel_type', Customer::class)
            ->where('rel_id', $customer->id)
            ->first();
    }

    protected function currentCustomer(): ?Customer
    {
        $id = $this->currentCustomerId();
        return $id ? Customer::find($id) : null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Show once the user has a billing identity (a customer record).
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        return Customer::where('user_id', $user->id)->exists()
            || Customer::where('email', $user->email)->exists();
    }
}
