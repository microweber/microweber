<div>
    <x-microweber-ui::textarea name="{{ $this->optionKey }}"  wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}" />
</div>
