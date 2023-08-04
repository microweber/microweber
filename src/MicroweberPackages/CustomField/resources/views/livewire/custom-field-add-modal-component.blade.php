<div x-data="{showTab: 'main'}">

    <div class="m-4">
        @php
            $options = [
                'main' => 'Fields',
                'existing' => 'Existing fields',
            ];
        @endphp
        <x-microweber-ui::radio-modern x-model="showTab" selectedOption="main" :options="$options" />
    </div>

    <div class="row px-4 pb-4" x-show="showTab == 'main'">
       @foreach(mw()->ui->custom_fields() as $fieldType=>$fieldName)
        <div class="col-xl-3 col-md-4 col-6 hover-bg-light text-center py-4">
            <button wire:click="add('{{$fieldType}}','{{$fieldName}}')" type="button"
                    class="d-flex flex-column btn btn-link mx-auto text-decoration-none mw-custom-field-existing-item-btn">
                <span class="mw-custom-field-icon-text mw-custom-field-icon-{{$fieldType}}"></span>
                <span class="mw-custom-field-title small" title="{{ $fieldName }}">
                    {{ $fieldName }}
                </span>
                <span class="d-none">
                    {{ $fieldName }}
                </span>
            </button>
        </div>
        @endforeach
    </div>

    <div class="row px-4 pb-4" x-show="showTab == 'existing'">
        @foreach($existingFields as $existingField)
            <div class="col-xl-3 col-md-4 col-6 hover-bg-light text-center py-4">
                <button wire:click="addExisting('{{$existingField->id}}')" type="button"
                        class="d-flex flex-column btn btn-link mx-auto text-decoration-none mw-custom-field-existing-item-btn">
                    <span class="mw-custom-field-icon-text mw-custom-field-icon-{{$existingField->type}}"></span>
                    <span class="mw-custom-field-title small" title="{{ $existingField->name }}">
                    {{ $existingField->name }}
                </span>
                    <span class="d-none">
                    {{ $existingField->name }}
                </span>
                </button>
            </div>
        @endforeach
    </div>

</div>
