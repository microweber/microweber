<div>
    <x-microweber-ui::simple-text-editor name="{{ $this->optionKey }}"  wire:model.debounce.100ms="state.settings.{{ $this->optionKey }}" />
</div>
