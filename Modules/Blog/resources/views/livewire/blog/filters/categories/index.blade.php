@if(isset($showCategories) and $showCategories)
    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <h5>{{ _e('Categories') }}</h5>
                <div class="d-flex flex-column gap-2">
                    @php
                        $categories = \Modules\Category\Models\Category::where('parent_id', 0)
                            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
                            ->get();
                    @endphp
                    @foreach($categories as $category)
                        @include('modules.blog::livewire.blog.filters.categories.category-child', ['category' => $category])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
