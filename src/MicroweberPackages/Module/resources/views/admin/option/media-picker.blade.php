<div wire:ignore>
    <x-microweber-ui::media-picker wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}" />
</div>
