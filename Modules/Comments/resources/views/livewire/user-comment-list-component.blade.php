<div>

    @forelse($comments as $comment)

        <livewire:comments::user-comment-preview
            wire:key="user-comment-preview-id-{{$comment->id}}"
            :comment="$comment" />

    @empty

        @include('modules.comments::no-comments')
    @endforelse

    @if($comments->hasPages())
        <div class="d-flex justify-content-center mb-3">
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
