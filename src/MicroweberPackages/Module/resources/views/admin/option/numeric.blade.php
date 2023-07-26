<div>
    <x-microweber-ui::input type="number" wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}" />
</div>
