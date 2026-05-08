<div class="text-left mw-shop-filter-custom-fields">
    {{-- AI-67 / TICKET-ZZ (cycle-74 2026-05-08): each custom-field
         block is a GROUP of related checkboxes — the canonical
         a11y pattern is <fieldset> + <legend>. Without these,
         screen-reader users get an unannounced row of orphaned
         checkboxes; with them they hear "Color, group: Red /
         Blue / Green …" so they know which filter the checkbox
         applies to. --}}
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
