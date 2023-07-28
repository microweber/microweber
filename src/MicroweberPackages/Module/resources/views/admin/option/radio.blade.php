
@php

$selectedOption = '';
if (isset($this->state['settings']) and isset($this->state['settings'][$this->optionKey])) {
    $selectedOption = $this->state['settings'][$this->optionKey];
}

@endphp
<div>
    <x-microweber-ui::radio
        wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}"
        :selectedOption="$selectedOption"
        :options="$radioOptions" />
</div>
