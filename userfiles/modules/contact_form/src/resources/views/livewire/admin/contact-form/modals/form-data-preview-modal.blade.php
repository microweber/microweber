<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog" role="document">
            <div class="mw-modal-content">
                @if(!$formData)
                    <h1>No form data found</h1>
                @else
                    <div class="mw-modal-header">
                        <h5 class="mw-modal-title">
                            {{$formData->getSubject()}}
                        </h5>
                        <button type="button" class="btn-close" wire:click="$emit('closeModal')"
                                aria-label="Close"></button>
                    </div>

                    <div class="mw-modal-body py-4">

                        @foreach($formData->getFormDataValues() as $formDataValue)
                            <b>{{$formDataValue['field_name'] }}:</b><br/>
                            <div style="word-wrap: break-word">
                                {!! $formDataValue['field_value'] !!}
                            </div>
                            <br/>
                        @endforeach

                        <div class="mt-4 d-flex justify-content-between">

                            @if($confirmingDeleteId === $formData->id)
                                <button type="button" wire:click="delete({{ $formData->id }})"
                                        class="btn btn-outline-danger mt-3">
                                    {{_e('Are you sure you want to delete this email?')}}
                                </button>
                            @else
                                <button type="button" wire:click="confirmDelete({{ $formData->id }})"
                                        class="btn btn-outline-danger mt-3">
                                    {{_e('Delete')}}
                                </button>
                            @endif

                            <button class="btn btn-outline-dark mt-3"
                                    wire:click="$emit('closeModal')"> {{_e('Close')}}</button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
