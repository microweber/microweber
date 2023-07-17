<div>
    <label class="form-control-live-edit-label-wrapper">
        <input type="text" placeholder="Type your text..." class="form-control-live-edit-input" wire:model.debounce.100ms="state.settings.{{ $this->optionName }}"/>

        <span class="form-control-live-edit-bottom-effect"></span>
    </label>
</div>
