<x-filament::page>
    {{ $this->form }}
    <div class="mt-4">
        <x-filament::button wire:click="submit" color="primary">
            Update Subscription
        </x-filament::button>
    </div>
</x-filament::page>