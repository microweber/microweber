<div wire:ignore>
    <x-microweber-ui::simple-text-editor name="{{ $this->optionKey }}" wire:model.live.debounce.500ms="state.settings.{{ $this->optionKey }}"  />
</div>
