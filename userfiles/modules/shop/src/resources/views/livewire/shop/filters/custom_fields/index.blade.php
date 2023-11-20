<div class="mt-4 text-left">

    <div>
        @foreach($availableCustomFields as $customField)
            <div>{{$customField->name}}</div>
            <div>
                <ul>
                    @foreach($customField->values as $customFieldValue)
                    <li>
                        {{$customFieldValue->value}}
                    </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
    <button type="button" wire:click="filterClearCustomFields()" class="btn btn-outline-danger btn-sm mt-2">
        Clear All
    </button>
</div>
