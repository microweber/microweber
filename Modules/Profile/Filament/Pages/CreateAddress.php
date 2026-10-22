<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Modules\Address\Models\Address;
use Modules\Profile\Filament\Pages\Concerns\HasAddressForm;

/**
 * Dedicated "Add New Address" page (replaces the tall modal on the Addresses
 * table, which overflowed small screens). Hidden from the sidebar; reached from
 * the Addresses page's "Add New Address" button.
 */
class CreateAddress extends Page
{
    use HasAddressForm;

    protected string $view = 'modules.profile::pages.address-form';

    protected static bool $shouldRegisterNavigation = false;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function getTitle(): string
    {
        return __('Add New Address');
    }

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema($this->addressFormComponents())
            ->statePath('data')
            ->columns(1);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $customer = $this->resolveCurrentCustomer();
        if ($customer) {
            $data['rel_type'] = 'customer';
            $data['rel_id'] = $customer->id;
        }

        Address::create($data);

        Notification::make()
            ->title(__('Address Added'))
            ->body(__('Your address has been saved successfully.'))
            ->success()
            ->send();

        $this->redirect(SavedAddresses::getUrl());
    }
}
