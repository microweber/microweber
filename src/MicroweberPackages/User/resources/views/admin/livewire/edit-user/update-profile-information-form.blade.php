<div>
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

            <div class="form-group mt-4 mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="form-check-input" id="send_new_user_email" checked="">
                    <label class="custom-control-label" for="send_new_user_email"><?php _e("Send the new user an email about their account"); ?>. <br/>
                    </label>
                    <br />
                    <a href="<?php echo admin_url();?>settings?group=users" target="_blank"><?php _e("Edit e-mail template"); ?>.</a>
                </div>
            </div>

            <div class="col-span-6 sm:col-span-4 mt-2">
                <button type="button" class="btn btn-outline-primary" wire:click="$emit('openModal', 'admin::edit-user.update-password-without-confirm-form-modal', {{ json_encode(['userId' => $state['id']]) }})">Change Password</button>
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

</div>
