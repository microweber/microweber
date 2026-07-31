<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">
                Save CSS
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
