<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                {{ __('Save Changes') }}
            </x-filament::button>
        </div>

        @livewire('user::profile.two-factor-authentication-form')


        @livewire('user::profile.logout-other-browser-sessions-form')

    </form>
</x-filament-panels::page>
