<div>
    <div>
        <div class="modal-header">
            <h5 class="modal-title">
                {{$title}}
            </h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body text-center">

            <div>
                {{$body}}
            </div>

            <div class="mt-4">
                <button type="button" wire:click="confirm" class="btn btn-outline-primary">
                    {{$button_text}}
                </button>
                <button type="button" wire:click="$emit('closeModal')" class="btn btn-outline-primary">
                    {{_e('Cancel')}}
                </button>
            </div>

        </div>
    </div>
</div>
