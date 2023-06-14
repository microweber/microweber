<div>

    <form>
        <div class="mt-2">
            <label>Comment:</label>
            <x-comments::editors.textarea model="comment.comment_body" />
        </div>

        <div class="mt-2">
            <button wire:loading.attr="disabled" wire:click="save" type="button" class="btn btn-outline-primary">
                <div wire:loading wire:target="save">
                    <i class="fa fa-spinner fa-spin"></i> {{_e('Posting comment...')}}
                </div>
                <div wire:loading.remove wire:target="save">
                    {{_e('Reply comment')}}
                </div>
            </button>
        </div>
    </form>

</div>
