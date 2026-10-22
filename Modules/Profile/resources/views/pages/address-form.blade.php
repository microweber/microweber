<x-filament-panels::page>
    <form wire:submit="save" class="max-w-2xl">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit">
                {{ __('Save Address') }}
            </x-filament::button>

            <x-filament::button
                tag="a"
                color="gray"
                :href="\Modules\Profile\Filament\Pages\SavedAddresses::getUrl()">
                {{ __('Cancel') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
