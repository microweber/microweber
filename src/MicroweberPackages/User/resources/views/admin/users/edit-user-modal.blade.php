<div>
    <form wire:submit.prevent="save">
        <div class="modal-header">
            <h5 class="modal-title">
                Edit User
            </h5>
            <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">Close</button>
        </div>
        <div class="modal-body">
            <div class="mt-2">
                First Name <br/>
                <input type="text" class="form-control" wire:model="state.first_name">
            </div>
            <div class="mt-2">
                Last Name <br/>
                <input type="text" class="form-control" wire:model="state.last_name">
            </div>
            <div class="mt-2">
                Email <br/>
                <input type="text" disabled="disabled" class="form-control" wire:model="state.email">
            </div>
            <div class="mt-2">
                Username <br/>
                <input type="text" disabled="disabled" class="form-control" wire:model="state.username">
            </div>
            <div class="mt-2">
                Created at: {{ $state['created_at'] }} <br />
                Updated at: {{ $state['updated_at'] }}
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
