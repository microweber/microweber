<div>
    <div class="px-4 py-4 text-black-50">
       <h3> {{ __('Add Custom Field')}}</h3>
    </div>
    <div class="row px-4">

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
</div>
