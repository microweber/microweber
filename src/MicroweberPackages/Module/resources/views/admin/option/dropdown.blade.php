

<div>
    <x-microweber-ui::select wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}" :options="$dropdownOptions" />
</div>
