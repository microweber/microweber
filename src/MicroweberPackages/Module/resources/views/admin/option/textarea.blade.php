<div>
    <x-microweber-ui::textarea name="{{ $this->optionKey }}"  wire:model.debounce.500ms="state.settings.{{ $this->optionKey }}" />
</div>
