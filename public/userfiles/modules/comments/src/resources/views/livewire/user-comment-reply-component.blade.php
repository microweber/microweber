<div>

    <div class="alert alert-success" @if(!$successMessage) style="display:none" @endif>
        {{$successMessage}}
        <div class="btn btn-outline-primary" wire:click="clearSuccessMessage">
            {{_e('Ok')}}
        </div>
    </div>

    <form>

        @if (!user_id())
            @if($allowAnonymousComments)
            <div class="row">
                <div class="col">
                    <label>Name:</label>
                    <input type="text" wire:model.lazy="state.comment_name" class="form-control" />
                </div>
                @if($errors->has('state.comment_name'))
                    <span>{{ $errors->first('state.comment_name') }}</span>
                @endif
                <div class="col">
                    <label>Email:</label>
                    <input type="email" wire:model.lazy="state.comment_email" class="form-control" />
                </div>
                @if($errors->has('state.comment_email'))
                    <span>{{ $errors->first('state.comment_email') }}</span>
                @endif
            </div>
            @else
                <div class="alert alert-warning">
                    {{_e('You must be logged in to post a comment.')}}
                </div>
            @endif
        @endif


        @if($allowToComment)

            <div class="mt-2">

                <label>Comment:</label>
                <x-comments::editors.textarea model="state.comment_body" />
                @if($errors->has('state.comment_body'))
                    <span>{{ $errors->first('state.comment_body') }}</span>
                @endif
            </div>

            <div class="mt-2">
                <button wire:loading.attr="disabled" wire:click="save" type="button" class="btn btn-outline-primary">
                    <div wire:loading wire:target="save">
                        <i class="fa fa-spinner fa-spin"></i> {{_e('Posting comment...')}}
                    </div>
                    <div wire:loading.remove wire:target="save">
                        {{_e('Post comment')}}
                    </div>
                </button>
            </div>
        @endif

    </form>

</div>
