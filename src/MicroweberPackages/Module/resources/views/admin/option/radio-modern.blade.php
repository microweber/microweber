
@php

    $selectedOption = '';
    if (isset($this->state['settings']) and isset($this->state['settings'][$this->optionKey])) {
        $selectedOption = $this->state['settings'][$this->optionKey];
    }

@endphp

<div wire:ignore>
    <x-microweber-ui::radio-modern
        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"
        :selectedOption="$selectedOption"
        :options="$options" />
</div>
