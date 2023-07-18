<div>
    @php
    $value = '';
    if (isset($this->state['settings'][$this->optionName])) {
        $value = $this->state['settings'][$this->optionName];
    }
    @endphp

    <x-microweber-ui::icon-picker :value="$value" wire:model.debounce.100ms="state.settings.{{ $this->optionName }}" />

</div>
