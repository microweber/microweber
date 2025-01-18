<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-start">
            @if($showUserAvatar)
                <img src="{{ $comment->getAvatarUrl() }}" 
                     alt="{{ $comment->comment_name }}" 
                     class="rounded-circle me-3"
                     width="40" 
                     height="40">
            @endif
            
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold mb-0">{{ $comment->comment_name }}</h6>
                        <small class="text-muted">
                            {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    
                    @if(auth()->check() && auth()->id() == $comment->created_by)
                        <div class="dropdown">
                            <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button wire:click="dispatch('deleteComment', { commentId: {{ $comment->id }} })" 
                                            class="dropdown-item text-danger">
                                        {{ _e('Delete') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="comment-content mb-2">
                    {!! $comment->commentBodyDisplay() !!}
                </div>

                @if($allowReplies)
                    <div class="d-flex align-items-center">
                        <button wire:click="dispatch('showReplyForm', { commentId: {{ $comment->id }} })" 
                                class="btn btn-sm btn-outline-primary">
                            {{ _e('Reply') }}
                        </button>
                    </div>
                @endif

                @if(count($comment->replies) > 0)
                    <div class="mt-4 ms-4">
                        @foreach($comment->replies as $reply)
                            <livewire:comments::user-comment-preview
                                wire:key="user-comment-preview-id-{{$reply->id}}"
                                :comment="$reply" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
