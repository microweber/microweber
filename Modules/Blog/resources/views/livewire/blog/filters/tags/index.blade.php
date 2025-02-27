@if($showTags)
    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <h5>{{ _e('Tags') }}</h5>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $tags = \Modules\Tag\Models\Tag::where('rel_type', morph_name(\Modules\Content\Models\Content::class))
                            ->groupBy('name')
                            ->get();
                    @endphp
                    @foreach($tags as $tag)
                        @include('modules.blog::livewire.blog.filters.tags.tag-button', ['tag' => $tag])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
