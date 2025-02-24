<button 
    wire:click="selectCategory({{ $category->id }})"
    class="btn {{ $selectedCategory == $category->id ? 'btn-primary' : 'btn-outline-primary' }} text-start w-100">
    {{ $category->title }}
    @if($category->children->count() > 0)
        <span class="float-end">
            <small>({{ $category->children->count() }})</small>
        </span>
    @endif
</button>

@if($category->children->count() > 0)
    <div class="ms-4 mt-2">
        @foreach($category->children as $child)
            @include('modules.blog::livewire.blog.filters.categories.category-child', ['category' => $child])
        @endforeach
    </div>
@endif
