<div class="card h-100 mw-blog-post-card">
    <a class="text-decoration-none" href="{{content_link($post->id)}}">
        {{-- AI-282 fix: previous markup used `height="350px"` and `width="100%"`
             as HTML attributes — both are invalid (HTML img height/width expect
             unitless integers). Some browsers fell back to the source image's
             natural 900×900 dimensions, blowing each card up to ~900px tall on
             mobile and producing the 33,539px module overflow reported in the
             tester audit. Move sizing to CSS via an explicit aspect-ratio so
             every card is the same height regardless of the source image. --}}
        <img src="{{app()->content_repository->getThumbnail($post->id,900,900, true)}}"
             alt="{{ $post->title }}"
             loading="lazy"
             class="card-img-top mw-blog-post-card__img">
        <div class="card-body">
            <h4 class="card-title">{{$post->title}}</h4>
            <p class="card-text">{!! $post->shortDescription(220) !!}</p>

            {{-- task-2026-05-22-7c7804 / AI-905: gate categories and tags on module settings.
                 $showCategories / $showTags are passed from BlogComponent::render() via
                 default.blade.php; defaults to true if the template is rendered standalone. --}}
            @if(($showCategories ?? true) && $post->categoryItems->count() > 0)
                <div class="post-categories mb-2">
                    @foreach($post->categoryItems as $categoryItem)
                        @if($categoryItem->category)
                            <span class="badge bg-secondary">{{ $categoryItem->category->title }}</span>
                        @endif
                    @endforeach
                </div>
            @endif

            @if(($showTags ?? true) && $post->tagged->count() > 0)
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
.mw-blog-post-card__img {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
}

.post-categories .badge {
    margin-right: 0.25rem;
}

.post-tags .badge {
    margin-right: 0.25rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}
</style>
