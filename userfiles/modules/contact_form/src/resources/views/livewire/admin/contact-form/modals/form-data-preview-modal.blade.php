<div>

    <div class="modal-header">
        <h5 class="modal-title">
            {{$formData->getSubject()}}
        </h5>
        <button type="button" class="btn-close" wire:click="$emit('closeModal')" aria-label="Close"></button>
    </div>

    <div class="modal-body py-4">

        @foreach($formData->getFormDataValues() as $formDataValue)
            <b>{{$formDataValue['field_name'] }}:</b><br />
            <div style="word-wrap: break-word">
                {!! $formDataValue['field_value'] !!}
            </div>
            <br />
        @endforeach

        <div class="mt-4 text-end">
            <a class="btn btn-dark me-2" href="" wire:click="$emit('closeModal')" > {{_e('Close')}}</a>
        </div>
    </div>

</div>
