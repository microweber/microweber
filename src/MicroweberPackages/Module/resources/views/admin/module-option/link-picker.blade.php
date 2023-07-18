<div>
    <div wire:ignore>
        <x-microweber-ui::link-picker wire:model.debounce.100ms="state.settings.{{ $this->optionName }}" />
    </div>
</div>
