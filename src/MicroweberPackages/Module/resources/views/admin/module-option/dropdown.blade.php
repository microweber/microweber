

<div>
    <x-microweber-ui::select wire:model.debounce.100ms="state.settings.{{ $this->optionName }}" :options="$dropdownOptions" />
</div>
