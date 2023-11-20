<div class="mt-4 text-left">
    <div>Categories</div>
    <ul class="mt-2 list-group list-group-flush">
        <li>
           <span class="list-group-item list-group-item-action @if(!$filteredCategory) active @endif"
                       wire:click="filterClearCategory()">
               All Categories
            </span>
        </li>
        @foreach($availableCategories as $category)
            @include('microweber-module-shop::livewire.shop.filters.categories.category-child', ['category' => $category])
        @endforeach
    </ul>
</div>
