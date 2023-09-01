<x-microweber-ui::form-section submit="createProfileInformation">
    <x-slot name="title">
        Profile Information
    </x-slot>

    <x-slot name="description">
        Account profile information and email address.
    </x-slot>

    <x-slot name="form">

        <!-- Username -->
        <div class="col-span-6 sm:col-span-4">
            <x-microweber-ui::label for="username" value="Username" />
            <x-microweber-ui::input id="username" type="text" class="mt-1 block w-full" wire:model.defer="state.username" />
            <x-microweber-ui::input-error for="username" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="live-edit-label">
            <x-microweber-ui::label for="first_name" value="First Name" />
            <x-microweber-ui::input id="first_name" type="text" class="mt-1 block w-full" wire:model.defer="state.first_name" />
            <x-microweber-ui::input-error for="first_name" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="live-edit-label">
            <x-microweber-ui::label for="last_name" value="Last Name" />
            <x-microweber-ui::input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" />
            <x-microweber-ui::input-error for="last_name" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="live-edit-label">
            <x-microweber-ui::label for="phone" value="Phone" />
            <x-microweber-ui::input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="state.phone" />
            <x-microweber-ui::input-error for="phone" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="live-edit-label">
            <x-microweber-ui::label for="email" value="Email" />
            <x-microweber-ui::input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-microweber-ui::input-error for="email" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="live-edit-label">
            <x-microweber-ui::label for="password" value="Password" />
            <x-microweber-ui::input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" />
            <x-microweber-ui::input-error for="password" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="col-span-6 sm:col-span-4 my-3">
            <x-microweber-ui::label for="role" value="Role of the user" />
            <div class="text-muted mb-2">{{ _e('Choose the current role of the user')}}. </div>
            <select id="role" class="form-select" wire:model.defer="state.is_admin">
                <option value="0">{{ _e('User')}}</option>
                <option value="1">{{ _e('Admin')}}</option>
            </select>
            <x-microweber-ui::input-error for="role" class="mt-2" />
        </div>

        <!-- Is Active -->
        <div class="col-span-6 sm:col-span-4 my-3">
            <x-microweber-ui::label for="is_active" value="Is Active?" />
            <div class="text-muted mb-2">{{ _e('Choose the current status of this user')}}</div>
            <select id="is_active" class="form-select" wire:model.defer="state.is_active">
                <option value="1">{{ _e('Active')}}</option>
                <option value="0">{{ _e('Disabled')}}</option>
            </select>
            <x-microweber-ui::input-error for="is_active" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-microweber-ui::action-message class="mr-3" on="created">
            Created.
        </x-microweber-ui::action-message>

        <x-microweber-ui::button>
            Create
        </x-microweber-ui::button>
    </x-slot>
</x-microweber-ui::form-section>

