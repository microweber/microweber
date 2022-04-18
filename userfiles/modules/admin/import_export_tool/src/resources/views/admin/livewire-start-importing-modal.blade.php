<form wire:submit.prevent="save">
    <div class="modal-header">
        <h5 class="modal-title">Feed importing</h5>
        <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">Close</button>
    </div>
    <div class="modal-body">
        <div>
            Importing content...
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
            </div>

        </div>
    </div>
    <div class="modal-footer">


    </div>
</form>
