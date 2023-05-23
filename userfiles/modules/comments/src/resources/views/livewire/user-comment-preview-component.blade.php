<div>
    <div class="d-flex flex-start mb-4" x-data="{
        showReplyForm: false
    }">
        <div class="shadow-1-strong me-3">
            <i class="fa fa-user-circle-o" style="font-size:42px"></i>
        </div>
        <div class="card w-100">
            <div class="card-body">
                <h5>{{$comment->comment_name}}</h5>
                <p class="text-small">
                    {{$comment->created_at->diffForHumans()}}
                </p>
                <div class="mt-3 mb-3">

                    {{$comment->comment_body}}

                    <div class="d-flex justify-content-between align-items-center mt-4">

                        <button wire:click="delete" class="btn btn-danger btn-sm">
                            <div wire:loading="delete">
                                <i class="fa fa-spinner fa-spin"></i> {{_e('Deleting comment...')}}
                            </div>
                            <div wire:loading.remove="delete">
                                {{_e('Delete comment')}}
                            </div>
                        </button>

                        <span @click="showReplyForm = ! showReplyForm" style="cursor:pointer" class="link-muted">
                            <i class="fa fa-reply me-1"></i> {{_e('Reply to')}} {{$comment->comment_name}}
                        </span>
                    </div>
                </div>

                <div x-show="showReplyForm" style="display:none; background:#fff;" class="mt-2 mb-4 p-4">
                    <div class="mb-4">
                        <b> <i class="fa fa-reply me-1"></i> {{_e('Reply to')}} {{$comment->comment_name}} </b>
                    </div>
                    <div>
                           <livewire:comments::user-comment-reply wire:key="user-comment-reply-id-{{$comment->id}}" rel_id="{{$comment->rel_id}}" reply_to_comment_id="{{$comment->id}}" />
                     </div>
                </div>

                <div>
                    @foreach($comment->replies as $reply)
                        <div>
                            <livewire:comments::user-comment-preview wire:key="user-comment-preview-reply-id-{{$reply->id}}" comment_id="{{$reply->id}}" />
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
