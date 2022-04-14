<div class="modal-header">
    <h5 class="modal-title">Add new feed import</h5>
    <button type="button" class="btn btn-link" x-on:click="closeModalOnClickAway()">Close</button>
</div>
<div class="modal-body">
    <div>
        Feed Name <br />
        <input type="text" class="form-control" wire:model="new_feed_name">
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
</div>

