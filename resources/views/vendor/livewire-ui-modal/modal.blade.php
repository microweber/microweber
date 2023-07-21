<div>
    <div id="js-modal-livewire-ui">
        @forelse($components as $id => $component)
            <div wire:key="{{ $id }}">
                @livewire($component['name'], $component['attributes'], key($id))
            </div>
        @empty
        @endforelse
    </div>
</div>
