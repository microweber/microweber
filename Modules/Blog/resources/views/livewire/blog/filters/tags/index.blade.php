@if(isset($showTags) and $showTags)
    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <h5>{{ _e('Tags') }}</h5>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        // Tags are global (Conner tagging) and link to content via
                        // the tagging_tagged pivot (taggable_type/taggable_id) — the
                        // tagging_tags table has no `rel_type` column. Resolve the
                        // tag names actually used by content, then load those tags.
                        $usedTagNames = \Illuminate\Support\Facades\DB::table('tagging_tagged')
                            ->where('taggable_type', morph_name(\Modules\Content\Models\Content::class))
                            ->distinct()
                            ->pluck('tag_name');
                        $tags = $usedTagNames->isEmpty()
                            ? collect()
                            : \Modules\Tag\Models\Tag::whereIn('name', $usedTagNames)->get();
                    @endphp
                    @foreach($tags as $tag)
                        @include('modules.blog::livewire.blog.filters.tags.tag-button', ['tag' => $tag])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
