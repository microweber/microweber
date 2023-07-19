

<div>
    <x-microweber-ui::radio-modern wire:model.debounce.100ms="state.settings.{{ $this->optionName }}" :options="$radioModernOptions" />
</div>
