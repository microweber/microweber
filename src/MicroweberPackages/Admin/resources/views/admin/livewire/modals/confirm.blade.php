<div>
    <div>
        <div class="modal-header">
            <h5 class="modal-title">{{_e('Confirm this action')}}</h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body">

            <p>
                {{_e('Please confirm this action')}}
            </p>

            <br />
            <button type="button" wire:click="confirm" class="btn btn-primary">
                Confirm action
            </button>

        </div>
    </div>
</div>
