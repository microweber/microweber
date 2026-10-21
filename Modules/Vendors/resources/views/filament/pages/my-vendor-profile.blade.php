<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit">
                {{ __('Save Changes') }}
            </x-filament::button>

            @if ($vendor && $vendor->slug)
                <x-filament::button
                    tag="a"
                    color="gray"
                    icon="heroicon-o-arrow-top-right-on-square"
                    href="{{ url('vendor/' . $vendor->slug) }}"
                    target="_blank">
                    {{ __('View my shop') }}
                </x-filament::button>
            @endif
        </div>
    </form>
</x-filament-panels::page>
