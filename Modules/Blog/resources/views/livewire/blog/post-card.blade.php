<div class="card h-100">
    <a class="text-decoration-none" href="{{content_link($post->id)}}">
        <img src="{{app()->content_repository->getThumbnail($post->id,900,900, true)}}"
             alt="{{ $post->title }}"
             height="350px"
             width="100%"
             class="card-img-top">
        <div class="card-body">
            <h4 class="card-title">{{$post->title}}</h4>
            <p class="card-text">{!! $post->shortDescription(220) !!}</p>

            @if($post->categoryItems->count() > 0)
                <div class="post-categories mb-2">
                    @foreach($post->categoryItems as $categoryItem)
                        @if($categoryItem->category)
                            <span class="badge bg-secondary">{{ $categoryItem->category->title }}</span>
                        @endif
                    @endforeach
                </div>
            @endif

            @if($post->tagged->count() > 0)
                <div class="post-tags">
                    @foreach($post->tagged as $tagged)
                        @if($tagged->tag)
                            <span class="badge bg-light text-dark">#{{ $tagged->tag->name }}</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </a>
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">{{$post->created_at->format('M j, Y')}}</small>
            @if($post->author)
                <small class="text-muted">{{ _e('By') }} {{ $post->author->name }}</small>
            @endif
        </div>
    </div>
</div>

<style>
.post-categories .badge {
    margin-right: 0.25rem;
}

.post-tags .badge {
    margin-right: 0.25rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}
</style>
