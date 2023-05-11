<div>
    <form wire:submit.prevent="confirm">
        <div class="modal-header">
            <h5 class="modal-title">
                {{_e('Uninstall')}} {{$moduleData['name']}} ?
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="$emit('closeModal')"></button>

        </div>
        <div class="modal-body">
                {{_e('Are you sure you want to uninstall')}} {{$moduleData['name']}} ?
        </div>
        <div class="modal-footer bg-light">
            <button type="submit" class="btn btn-danger">
                {{_e('Uninstall')}}
            </button>

            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close" wire:click="$emit('closeModal')">
                {{_e('Cancel')}}
            </button>

        </div>
    </form>
</div>
