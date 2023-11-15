<div>
    <input type="hidden" name="{{ $this->optionKey }}" wire:model.debounce.300ms="state.settings.{{ $this->optionKey }}" />
</div>
