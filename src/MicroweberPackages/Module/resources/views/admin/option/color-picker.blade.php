<div wire:ignore>
    <x-microweber-ui::color-picker

        :label="$label"

        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"

    />
</div>
