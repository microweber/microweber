<x-user::action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div>
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <div class="mt-3">
            <x-user::danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Delete Account') }}
            </x-user::danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-user::dialog-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Delete Account') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                <div class="mt-2 w-md-75" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-user::input type="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ __('Password') }}"
                                 x-ref="password"
                                 wire:model.defer="password"
                                 wire:keydown.enter="deleteUser" />

                    <x-user::input-error for="password" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-user::secondary-button wire:click="$toggle('confirmingUserDeletion')"
                                        wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-user::secondary-button>

                <x-user::danger-button wire:click="deleteUser" wire:loading.attr="disabled">
                    <div wire:loading wire:target="deleteUser" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    {{ __('Delete Account') }}
                </x-user::danger-button>
            </x-slot>
        </x-user::dialog-modal>
    </x-slot>

</x-user::action-section>
