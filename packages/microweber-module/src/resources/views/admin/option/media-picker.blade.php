<div wire:ignore>
    <x-microweber-ui::media-picker :label="$label" wire:model.live.debounce.100ms="state.settings.{{ $this->optionKey }}" />
</div>
