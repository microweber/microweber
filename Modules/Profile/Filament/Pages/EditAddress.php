<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\Attributes\Url;
use Modules\Address\Models\Address;
use Modules\Profile\Filament\Pages\Concerns\HasAddressForm;

/**
 * Dedicated "Edit Address" page (replaces the tall modal on the Addresses
 * table). Reached from the row "Edit" action with ?address={id}; ownership is
 * enforced so a user can only edit their own addresses. Hidden from the sidebar.
 */
class EditAddress extends Page
{
    use HasAddressForm;

    protected string $view = 'modules.profile::pages.address-form';

    protected static bool $shouldRegisterNavigation = false;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** Address id from the ?address= query string (set by the Edit link). */
    #[Url]
    public ?int $address = null;

    public function getTitle(): string
    {
        return __('Edit Address');
    }

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);

        $record = $this->resolveOwnedAddress($this->address);
        abort_unless($record !== null, 404);

        $this->form->fill($record->toArray());
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
        $record = $this->resolveOwnedAddress($this->address);
        abort_unless($record !== null, 404);

        $record->update($this->form->getState());

        Notification::make()
            ->title(__('Address Updated'))
            ->body(__('Your address has been updated successfully.'))
            ->success()
            ->send();

        $this->redirect(SavedAddresses::getUrl());
    }
}
