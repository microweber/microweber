<div>
    <select class="form-select" wire:model.debounce.100ms="state.settings.{{ $this->optionName }}">
        <option value="" disabled="disabled">Select option</option>
        @if(!empty($dropdownOptions))
            @foreach($dropdownOptions as $key => $option)
                <option value="{{ $key }}">{{ $option }}</option>
            @endforeach
        @endif
    </select>
</div>
