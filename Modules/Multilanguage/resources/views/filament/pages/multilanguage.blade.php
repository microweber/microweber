<x-filament-panels::page>
    <div>
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button
                type="button"
                color="secondary"
                wire:click="testGeoApi">
                Test Geo API
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
