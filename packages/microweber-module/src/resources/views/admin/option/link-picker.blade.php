<div>
    <div wire:ignore>
        <x-microweber-ui::link-picker wire:model.live.debounce.100ms="state.settings.{{ $this->optionKey }}" />
    </div>
</div>
