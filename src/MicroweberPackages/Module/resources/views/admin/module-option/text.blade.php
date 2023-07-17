<div>
    NIKI-text input
    <input type="text" class="form-control" wire:model.debounce.100ms="state.settings.{{ $this->optionName }}"/>
</div>
