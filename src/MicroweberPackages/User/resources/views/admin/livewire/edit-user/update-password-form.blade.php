<x-microweber-ui::form-section submit="updatePassword">
    <x-slot name="title">
        Update Password
    </x-slot>

    <x-slot name="description">
        Ensure your account is using a long, random password to stay secure.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-microweber-ui::label for="current_password" value="Current Password" />
            <x-microweber-ui::input id="current_password" type="password" class="mt-1 block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
            <x-microweber-ui::input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-microweber-ui::label for="password" value="New Password" />
            <x-microweber-ui::input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
            <x-microweber-ui::input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-microweber-ui::label for="password_confirmation" value="Confirm Password" />
            <x-microweber-ui::input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
            <x-microweber-ui::input-error for="password_confirmation" class="mt-2" />
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
