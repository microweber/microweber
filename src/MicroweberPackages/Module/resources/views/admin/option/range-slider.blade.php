<div wire:ignore>
    <x-microweber-ui::range-slider

        label="{{$label}}"

        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"
        selectedRange="{{$this->state['settings'][$this->optionKey]}}"

    />
</div>
