<div>
    <div>
        <div class="modal-header">
            <h5 class="modal-title">
                {{$title}}
            </h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body">

            {{$body}}

            <br />
            <br />
            <br />
            <button type="button" wire:click="confirm" class="btn btn-outline-primary">
                {{$button_text}}
            </button>

        </div>
    </div>
</div>
