<div>
    <x-microweber-ui::form-section submit="updateProfileInformation">
        <x-slot name="title">
            {{ _e('Profile Information') }}
        </x-slot>

        <x-slot name="description">
            {{ _e('Update your account profile information and email address') }}.
        </x-slot>

        <x-slot name="form">

            <!-- Profile Photo -->
            <div class="form-label mb-3 text-center" x-data="{}">
                <!-- Profile Photo File Input -->

                <script>
                    mw.require("files.js");
                </script>
                <script type="text/javascript">
                    $(document).ready(function () {
                        uploader = mw.files.uploader({
                            filetypes: "images",
                            element: mw.$("#profile-upload-image")
                        });

                        $(uploader).on("FileUploaded", function (a, data) {;
                            Livewire.emit('updateProfilePhoto', data.src);
                        });
                    });
                </script>

                <x-microweber-ui::label for="photo"  value="{{ _e('Profile image') }}" />

                <!-- Current Profile Photo -->

                @if(true)
                <div class="mt-2" id="profile-upload-image">
                    <img src="?time={{time()}}" class="rounded-circle"  height="60px" width="60px">
                </div>
                @else
                    <div id="profile-upload-image" class="mt-2 rounded-circle admin-users-no-user-img-wrapper bg-light d-flex align-items-center justify-content-center mx-auto" style="width:60px;height:60px">
                        <img src="{{modules_url()}}microweber/api/libs/mw-ui/assets/img/no-user.svg">
                    </div>
                @endif

                @if (true)
                    <x-microweber-ui::link-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        <div wire:loading wire:target="deleteProfilePhoto" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">{{ _e('Loading') }}...</span>
                        </div>

                        {{ _e('Remove photo') }}
                    </x-microweber-ui::link-button>
                @endif

            </div>

            <!-- Username -->
            <div class="live-edit-label">
                <x-microweber-ui::label for="username" value="Username" />
                <x-microweber-ui::input id="username" type="text" class="mt-1 block w-full" wire:model.defer="state.username" autocomplete="username" name="username" />
                <x-microweber-ui::input-error for="username" class="mt-2" />
            </div>

            <!-- First Name -->
            <div class="live-edit-label">
                <x-microweber-ui::label for="first_name" value="First Name" />
                <x-microweber-ui::input id="first_name" type="text" class="mt-1 block w-full" wire:model.defer="state.first_name" autocomplete="first_name"  name="first_name" />
                <x-microweber-ui::input-error for="first_name" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="live-edit-label">
                <x-microweber-ui::label for="last_name" value="Last Name" />
                <x-microweber-ui::input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" autocomplete="last_name"  name="last_name" />
                <x-microweber-ui::input-error for="last_name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="live-edit-label">
                <x-microweber-ui::label for="email" value="Email" />
                <x-microweber-ui::input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email"  name="email" />
                <x-microweber-ui::input-error for="email" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="live-edit-label">
                <x-microweber-ui::label for="phone" value="Phone" />
                <x-microweber-ui::input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="state.phone" name="phone" />
                <x-microweber-ui::input-error for="phone" class="mt-2" />
            </div>

            @if($userId)
            <div class="form-group mt-4 mb-4">
                <div class="custom-control custom-checkbox my-2">
                    <input type="checkbox" class="form-check-input" id="send_new_user_email" checked="">
                    <label class="custom-control-label" for="send_new_user_email"><?php _e("Send the new user an email about their account"); ?>. <br/>
                    </label>
                    <br />
                    <a href="<?php echo admin_url();?>settings?group=users" target="_blank"><?php _e("Edit e-mail template"); ?>.</a>
                </div>
            </div>
            <div class="live-edit-label">
                <button type="button" class="btn btn-outline-primary" wire:click="$emit('openModal', 'admin::edit-user.update-password-without-confirm-form-modal', {{ json_encode(['userId' => $state['id']]) }})">Change Password</button>
            </div>
            @endif

        </x-slot>

        <x-slot name="actions">
            <x-microweber-ui::action-message class="mr-3" on="saved">
                {{ _e('Saved') }}.
            </x-microweber-ui::action-message>

            <x-microweber-ui::button id="user-save-button">
                {{ _e('Save') }}
            </x-microweber-ui::button>
        </x-slot>
    </x-microweber-ui::form-section>

</div>
