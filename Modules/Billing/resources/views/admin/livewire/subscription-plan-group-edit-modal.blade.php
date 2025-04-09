<div>
    <form>
        <div class="modal-header">
            <h5 class="modal-title">Edit Subscription Plan Group</h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body">

            <div class="mt-2">
                Name <br/>
                <input type="text" required="required" class="form-control" wire:model="state.name">
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" wire:click="save" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
