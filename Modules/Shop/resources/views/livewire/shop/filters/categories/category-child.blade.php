@if(isset($category))
    <div class="mw-shop-filter-category-row">

        <button type="button"
                role="listitem"
                class="list-group-item list-group-item-action @if($category->id == $filteredCategory) active @endif"
                @if($category->id == $filteredCategory) aria-current="true" @endif
                wire:click="filterCategory('{{$category->id}}')">
            {{ $category->title }}
        </button>

        @if(!empty($category->children))
            <div class="mw-shop-filter-category-children">
                @foreach($category->children as $category)
                    @include('modules.shop::livewire.shop.filters.categories.category-child', ['category' => $category])
                @endforeach
            </div>
        @endif
    </div>
@endif
