<div wire:ignore>
    <x-microweber-ui::file-picker

        :label="$label"

        allowedType="{{$this->allowedType}}"

        wire:model.live.debounce.100ms="state.settings.{{ $this->optionKey }}" />

</div>
