<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" form="save">
                {{ __('Save Changes') }}
            </x-filament::button>
        </div>

        @livewire('user::profile.two-factor-authentication-form')


        @livewire('user::profile.logout-other-browser-sessions-form')

    </x-filament-panels::form>
</x-filament-panels::page>
