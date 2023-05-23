<div class="d-flex flex-start mb-4">
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

                <div class="d-flex justify-content-end align-items-center mt-4">
                    <span class="link-muted"><i class="fa fa-reply me-1"></i> Reply</span>
                </div>
            </div>

            <div style="background:#fff;" class="mt-2 mb-4 p-4 d-none-">
                @include('comments::comment_reply', [
                    'reply_to_comment_id' => $comment->id,
                ])
            </div>

            <div>
                @foreach($comment->replies as $reply)
                    @include('comments::comment_preview', [
                        'comment' => $reply,
                    ])
                @endforeach
            </div>

        </div>
    </div>
</div>
