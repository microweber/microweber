<div class="text-left mw-shop-filter-custom-fields">

    <h3 class="mw-shop-filter-heading h6 mb-2">{{ __('Filter by attributes') }}</h3>
    @foreach($availableCustomFields as $customField)
        <fieldset class="mw-shop-filter-cf-group mb-3 border-0 p-0">
            <legend class="mw-shop-filter-cf-legend small fw-semibold">{{ $customField->name }}</legend>
            <div class="mt-2 mb-2">
                @foreach($customField->values as $customFieldValue)
                    <label class="form-check">
                        <input class="form-check-input"
                               @if(isset($filteredCustomFields[$customField->name_key]) && in_array($customFieldValue->value, $filteredCustomFields[$customField->name_key])) checked="" @endif
                               wire:click="filterToggleCustomField('{{$customField->name_key}}','{{$customFieldValue->value}}')" type="checkbox">
                        <span class="form-check-label">{{ $customFieldValue->value }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>
    @endforeach

    @if(!empty($filteredCustomFields))
    <button type="button" wire:click="filterClearCustomFields()" class="btn btn-outline-danger btn-sm mt-2">
        {{ __('Clear All') }}
    </button>
    @endif
</div>
