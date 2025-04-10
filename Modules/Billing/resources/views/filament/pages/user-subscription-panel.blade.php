<x-filament-panels::page>

    <div class="mb-6">




    <div>
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button wire:click="submit" color="primary">
                Update Subscription
            </x-filament::button>
        </div>
    </div>

</x-filament-panels::page>
