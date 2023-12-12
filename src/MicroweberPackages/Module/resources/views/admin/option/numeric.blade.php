<div>
    <x-microweber-ui::input type="number" wire:model.debounce.300ms="state.settings.{{ $this->optionKey }}" />
</div>
