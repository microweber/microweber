<div>
    <x-microweber-ui::color-picker
        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"

    />
</div>
