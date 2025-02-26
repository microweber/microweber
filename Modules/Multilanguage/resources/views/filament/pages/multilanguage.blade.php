<x-filament-panels::page>
    <div>
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-4">
                <x-filament::button type="submit">
                    Save Settings
                </x-filament::button>

                <x-filament::button
                    type="button"
                    color="secondary"
                    wire:click="testGeoApi"
                    class="ml-2">
                    Test Geo API
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
