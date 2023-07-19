<div>
    @php
    $value = '';
    if (isset($this->state['settings'][$this->optionKey])) {
        $value = $this->state['settings'][$this->optionKey];
    }
    @endphp

    <x-microweber-ui::icon-picker :value="$value" wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}" />

</div>
