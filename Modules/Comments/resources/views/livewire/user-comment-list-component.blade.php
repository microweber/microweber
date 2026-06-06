<div>

    @forelse($comments as $comment)

        <livewire:comments::user-comment-preview
            wire:key="user-comment-preview-id-{{$comment->id}}"
            :comment="$comment" />

    @empty
        {{-- task-2026-06-06-cmtbind: render the no-comments empty state instead of a
             silent void (the bare @foreach had no empty branch). --}}
        @include('modules.comments::no-comments')
    @endforelse

    @if($comments->hasPages())
        <div class="d-flex justify-content-center mb-3">
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
