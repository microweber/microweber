<div>
    <x-microweber-ui::input name="{{ $this->optionKey }}" wire:model.debounce.500ms="state.settings.{{ $this->optionKey }}" />
</div>
