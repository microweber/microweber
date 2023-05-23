<div>

    @foreach($comments as $comment)
        <livewire:comments::user-comment-preview
            wire:key="user-comment-preview-id-{{$comment->id}}"
            comment_id="{{$comment->id}}" />
    @endforeach

    <div class="d-flex justify-content-center mb-3">
        {{ $comments->links("livewire-tables::specific.bootstrap-4.pagination") }}
    </div>

</div>
