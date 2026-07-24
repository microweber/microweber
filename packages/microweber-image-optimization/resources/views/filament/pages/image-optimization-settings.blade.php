<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Save Settings
            </x-filament::button>

            <x-filament::button color="gray" wire:click.prevent="clearCache" type="button" icon="heroicon-o-trash">
                Clear WebP Cache
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
