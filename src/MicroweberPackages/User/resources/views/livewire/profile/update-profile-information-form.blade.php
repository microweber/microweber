<x-user::form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">

        <x-user::action-message on="saved">
            {{ __('Saved.') }}
        </x-user::action-message>

        <!-- Profile Photo -->
        <div class="mb-3" x-data="{photoName: null, photoPreview: null}">

                <x-user::label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    @if ($this->user->profile_photo_path)
                    <img src="{{ $this->user->profile_photo_url }}" class="rounded-circle bg-light" height="40px" width="40px" style="width: 60px; height: 60px;">
                     @endif
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <img x-bind:src="photoPreview" class="rounded-circle bg-light" height="40px" width="40px" style="width: 60px; height: 60px;">
                </div>

                <x-user::primary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
				</x-user::primary-button>

				@if ($this->user->profile_photo_path)
                    <x-user::secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        <div wire:loading wire:target="deleteProfilePhoto" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                        {{ __('Remove Photo') }}
                    </x-user::secondary-button>
                @endif

                <x-user::input-error for="photo" class="mt-2" />
            </div>

        <div class="w-md-75">
            <!-- Name -->
            <div class="mb-3">
                <x-user::label for="name" value="{{ __('Name') }}" />
                <x-user::input id="name" type="text" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" wire:model.defer="state.name" autocomplete="name" />
                <x-user::input-error for="name" />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <x-user::label for="email" value="{{ __('Email') }}" />
                <x-user::input id="email" type="email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" wire:model.defer="state.email" />
                <x-user::input-error for="email" />
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
		<div class="d-flex align-items-baseline">
			<x-user::button>
                <div wire:loading class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

				{{ __('Save') }}
			</x-user::button>
		</div>
    </x-slot>
</x-user::form-section>
