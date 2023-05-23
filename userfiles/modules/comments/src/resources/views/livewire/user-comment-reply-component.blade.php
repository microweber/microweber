<div>
    <form>
        <div class="row">
            <div class="col">
                <label>Name:</label>
                <input type="text" wire:model.lazy="state.comment_name" class="form-control" />
            </div>
            <div class="col">
                <label>Email:</label>
                <input type="email" wire:model.lazy="state.comment_email" class="form-control" />
            </div>
        </div>
        <div class="mt-2">
            <label>Comment:</label>
            <textarea class="form-control" wire:model.lazy="state.comment_body"></textarea>
        </div>
        <div class="mt-2">
            <button wire:click="save" type="button" class="btn btn-outline-primary">
                <div wire:loading="save">
                    <i class="fa fa-spinner fa-spin"></i> {{_e('Posting comment...')}}
                </div>
                <div wire:loading.remove="save">
                    {{_e('Post comment')}}
                </div>
            </button>
        </div>
    </form>

</div>
