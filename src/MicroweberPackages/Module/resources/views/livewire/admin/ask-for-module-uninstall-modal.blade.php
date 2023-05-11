<div>
    <form wire:submit.prevent="confirm">
        <div class="modal-header">
            <h5 class="modal-title">
                {{_e('Uninstall')}} {{$moduleData['name']}} ?
            </h5>
            <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
        <div class="modal-body">
            <div>
                <p>
                    {{_e('Are you sure you want to uninstall')}} {{$moduleData['name']}} ?
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-danger">
                {{_e('Uninstall')}}
            </button>
            <button type="button" class="btn btn-outline-primary" wire:click="$emit('closeModal')">
                {{_e('Cancel')}}
            </button>
        </div>
    </form>
</div>
