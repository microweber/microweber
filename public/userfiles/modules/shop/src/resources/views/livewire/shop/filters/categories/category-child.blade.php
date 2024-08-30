@if(isset($category))
    <div>
        <span
            class="list-group-item list-group-item-action @if($category->id == $filteredCategory) active @endif"
            wire:click="filterCategory('{{$category->id}}')">
            {{ $category->title }}
        </span>

        @if(!empty($category->children))
            <div>
                @foreach($category->children as $category)
                    @include('microweber-module-shop::livewire.shop.filters.categories.category-child', ['category' => $category])
                @endforeach
            </div>
        @endif
    </div>
@endif
