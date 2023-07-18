<label class="form-control-live-edit-label-wrapper">

<select class="form-select form-control-live-edit-input" wire:model.debounce.100ms="state.settings.{{ $this->optionName }}">
        <option value="" disabled="disabled">Select option</option>
        @if(!empty($dropdownOptions))
            @foreach($dropdownOptions as $key => $option)
                <option value="{{ $key }}">{{ $option }}</option>
            @endforeach
        @endif
    </select>

    <span class="form-control-live-edit-bottom-effect"></span>

</label>



