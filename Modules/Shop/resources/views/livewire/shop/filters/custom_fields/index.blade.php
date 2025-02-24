<div class="text-left">

    <div>
        @foreach($availableCustomFields as $customField)
            <div>{{$customField->name}}</div>
            <div class="mt-2 mb-2">
                @foreach($customField->values as $customFieldValue)
                    <label class="form-check">
                        <input class="form-check-input"
                               @if(isset($filteredCustomFields[$customField->name_key]) && in_array($customFieldValue->value, $filteredCustomFields[$customField->name_key])) checked="" @endif
                               wire:click="filterToggleCustomField('{{$customField->name_key}}','{{$customFieldValue->value}}')" type="checkbox">
                        <span class="form-check-label">{{$customFieldValue->value}}</span>
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>

    @if(!empty($filteredCustomFields))
    <button type="button" wire:click="filterClearCustomFields()" class="btn btn-outline-danger btn-sm mt-2">
        Clear All
    </button>
    @endif
</div>
