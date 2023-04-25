<div>
    <x-microweber-ui::form-section submit="updateProfileInformation">
        <x-slot name="title">
            User status and role
        </x-slot>

        <x-slot name="description">
            Update your account's profile information and email address.
        </x-slot>

        <x-slot name="form">

            <!-- Role -->
            <div class="col-span-6 sm:col-span-4 mt-2">
                <x-microweber-ui::label for="role" value="Role of the user" />
                <div class="text-muted">Choose the current role of the user. </div>
                <select id="role" class="form-control" wire:model.defer="state.is_admin">
                    <option value="0">User</option>
                    <option value="1">Admin</option>
                </select>
                <x-microweber-ui::input-error for="role" class="mt-2" />
            </div>

            <!-- Is Active -->
            <div class="col-span-6 sm:col-span-4 mt-2">
                <x-microweber-ui::label for="is_active" value="Is Active?" />
                <div class="text-muted">Choose the current status of this user</div>
                <select id="is_active" class="form-control" wire:model.defer="state.is_active">
                    <option value="1">Active</option>
                    <option value="0">Disabled</option>
                </select>
                <x-microweber-ui::input-error for="is_active" class="mt-2" />
            </div>

            <br />
            <a href="{{api_url('users/export_my_data?user_id=' . $state['id'])}}" class="btn btn-outline-primary">Export user data</a>
            <button type="button" class="btn btn-outline-primary" wire:click="$emit('openModal', 'admin::user-tos-log', {{ json_encode(['userId' => $state['id']]) }})">Terms agreement log</button>
            <button type="button" class="btn btn-outline-primary" wire:click="$emit('openModal', 'admin::user-login-attempts', {{ json_encode(['userId' => $state['id']]) }})">Login attempts</button>

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
</div>
