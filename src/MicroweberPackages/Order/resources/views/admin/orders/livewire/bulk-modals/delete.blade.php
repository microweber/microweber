<div>
    <div class="modal-header">
        <h5 class="modal-title">Bulk Delete</h5>
        <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">
            <i class="fa fa-times text-muted"></i>
        </button>
    </div>
    <div class="modal-body">
        <div>
            Are you sure you want to delete the selected data? <br/>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" wire:click="$emit('closeModal')">Cancel</button>
        <button type="button" class="btn btn-success" wire:click="delete">Yes</button>
    </div>
</div>
