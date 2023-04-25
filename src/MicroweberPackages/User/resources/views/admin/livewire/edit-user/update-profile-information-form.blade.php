<x-microweber-ui::form-section submit="updateProfileInformation">
    <x-slot name="title">
        Profile Information
    </x-slot>

    <x-slot name="description">
        Update your account's profile information and email address.
    </x-slot>

    <x-slot name="form">

        <!-- Profile Photo -->
        <div class="mb-3" x-data="{photoName: null, photoPreview: null}">
            <!-- Profile Photo File Input -->
            <input type="file" hidden
                   wire:model="photo"
                   x-ref="photo"
                   x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <x-microweber-ui::label for="photo" value="{{ __('Photo') }}" />

            <!-- Current Profile Photo -->
            <div class="mt-2" x-show="! photoPreview">
                <img src="{{ $this->photo }}" class="rounded-circle" height="80px" width="80px">
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2" x-show="photoPreview">
                <img x-bind:src="photoPreview" class="rounded-circle" width="80px" height="80px">
            </div>

            <x-microweber-ui::secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Select A New Photo') }}
            </x-microweber-ui::secondary-button>

            @if ($this->photo)
                <x-microweber-ui::secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                    <div wire:loading wire:target="deleteProfilePhoto" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    {{ __('Remove Photo') }}
                </x-microweber-ui::secondary-button>
            @endif

            <x-microweber-ui::input-error for="photo" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="col-span-6 sm:col-span-4 mt-2">
            <x-microweber-ui::label for="username" value="Username" />
            <x-microweber-ui::input id="username" type="text" class="mt-1 block w-full" wire:model.defer="state.username" autocomplete="username" />
            <x-microweber-ui::input-error for="username" class="mt-2" />
        </div>

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

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4 mt-2">
            <x-microweber-ui::label for="phone" value="Phone" />
            <x-microweber-ui::input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="state.phone" />
            <x-microweber-ui::input-error for="phone" class="mt-2" />
        </div>

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
<script>
    import Options from "../../../../../../../../userfiles/modules/microweber/api/libs/chosen/options.html";
    export default {
        components: {Options}
    }
</script>
