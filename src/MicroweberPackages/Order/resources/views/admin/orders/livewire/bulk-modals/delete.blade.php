<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog">
            <div class="mw-modal-content">

                <div class="mw-modal-header">
                    <h5 class="modal-title">
                        {{_e('Bulk Delete')}}
                    </h5>
                    <button type="button" class="btn-close"   aria-label="Close" wire:click="$emit('closeModal')"></button>
                </div>

                <div class="mw-modal-body">
                    <div>
                        Are you sure you want to delete the selected data? <br/>
                    </div>
                </div>

                <div class="mw-modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component"  aria-label="Close"
                            wire:click="$emit('closeModal')">
                        {{_e('Cancel')}}
                    </button>

                    <button wire:click="delete" type="submit" class="mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component text-danger">
                        {{_e('Delete')}}
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>
