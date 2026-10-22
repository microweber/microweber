<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                {{ __('Save Changes') }}
            </x-filament::button>
        </div>
    </form>

    {{-- Change Password — moved here from the standalone sidebar page. --}}
    <x-filament::section collapsible>
        <x-slot name="heading">{{ __('Change Password') }}</x-slot>
        <x-slot name="description">{{ __('Update the password you use to sign in.') }}</x-slot>

        <form wire:submit="changePassword">
            {{ $this->passwordForm }}

            <div class="mt-6">
                <x-filament::button type="submit">
                    {{ __('Update Password') }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    @livewire('user::profile.two-factor-authentication-form')

    @livewire('user::profile.logout-other-browser-sessions-form')
</x-filament-panels::page>
