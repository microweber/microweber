@if(isset($category))
    <li>
        <span
            class="list-group-item list-group-item-action @if($category->id == $filteredCategory) active @endif"
            wire:click="filterCategory('{{$category->id}}')">
            {{ $category->title }}
        </span>

        @if(!empty($category->children))
            <ul>
                @foreach($category->children as $category)
                    @include('microweber-module-shop::livewire.shop.filters.categories.category-child', ['category' => $category])
                @endforeach
            </ul>
        @endif
    </li>
@endif
