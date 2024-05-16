<div>
    <x-microweber-ui::input name="{{ $this->optionKey }}" wire:model.live.debounce.500ms="state.settings.{{ $this->optionKey }}" />
</div>
