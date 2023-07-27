<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog">
            <div class="mw-modal-content">
                <div class="mw-modal-header">
                    <h5 class="mw-modal-title">
                        {{$title}}
                    </h5>
                    <button type="button" class="btn-close mw-process-close-modal-button"
                            wire:click="$emit('closeModal')"></button>
                </div>
                <div class="mw-modal-body text-center">

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
    </div>
</div>
