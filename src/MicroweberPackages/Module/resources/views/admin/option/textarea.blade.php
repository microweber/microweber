<div>
    <x-microweber-ui::textarea name="{{ $this->optionKey }}"  wire:model.debounce.300ms="state.settings.{{ $this->optionKey }}" />
</div>
