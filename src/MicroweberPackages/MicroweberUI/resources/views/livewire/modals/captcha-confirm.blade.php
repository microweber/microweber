<div>
    <div>
        <div class="modal-header">
            <h5 class="modal-title">
                Confirm you are not a robot
            </h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body text-center">

            <div>
                <module type="captcha" />
            </div>

            <div class="mt-4">
                <button type="button" wire:click="confirm" class="btn btn-outline-primary">
                    {{_e('Confirm')}}
                </button>
                <button type="button" wire:click="$emit('closeModal')" class="btn btn-outline-primary">
                    {{_e('Cancel')}}
                </button>
            </div>

        </div>
    </div>
</div>
