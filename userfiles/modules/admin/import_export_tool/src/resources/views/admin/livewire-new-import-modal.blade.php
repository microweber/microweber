
<script>
    Livewire.on('showModal', () => {
        $('#exampleModal').modal('show');
    });
    Livewire.on('hideModal', () => {
        $('#exampleModal').modal('hide');
    });
</script>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New import feed</h5>
            </div>
            <div class="modal-body">
                Feed Name <br />
                <input type="text" class="form-control" wire:model.lazy="new_feed_name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$emit('hideModal')">Close</button>
                <button type="button" class="btn btn-primary" wire:click="addNew">Save</button>
            </div>
        </div>
    </div>
</div>
