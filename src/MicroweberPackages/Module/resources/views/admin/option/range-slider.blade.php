<div wire:ignore>

    aaaade:::

    <x-microweber-ui::range-slider

        label="{{$label}}"
        labelUnit="{{$labelUnit}}"
        min="{{$min}}"
        max="{{$max}}"

        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"

        @if(isset($this->state['settings']))
            selectedRange="{{$this->state['settings'][$this->optionKey]}}"
        @endif

    />

</div>
