<x-microweber-ui::form-section submit="updateProfileInformation">
    <x-slot name="title">
        Profile Information
    </x-slot>

    <x-slot name="description">
        Update your account's profile information and email address.
    </x-slot>

    <x-slot name="form">

        <!-- First Name -->
        <div class="col-span-6 sm:col-span-4 mt-2">
            <x-microweber-ui::label for="first_name" value="First Name" />
            <x-microweber-ui::input id="first_name" type="text" class="mt-1 block w-full" wire:model.defer="state.first_name" autocomplete="first_name" />
            <x-microweber-ui::input-error for="first_name" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="col-span-6 sm:col-span-4 mt-2">
            <x-microweber-ui::label for="last_name" value="Last Name" />
            <x-microweber-ui::input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" autocomplete="last_name" />
            <x-microweber-ui::input-error for="last_name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4 mt-2">
            <x-microweber-ui::label for="email" value="Email" />
            <x-microweber-ui::input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-microweber-ui::input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-microweber-ui::action-message class="mr-3" on="saved">
            Saved.
        </x-microweber-ui::action-message>

        <x-microweber-ui::button>
            Save
        </x-microweber-ui::button>
    </x-slot>
</x-microweber-ui::form-section>
