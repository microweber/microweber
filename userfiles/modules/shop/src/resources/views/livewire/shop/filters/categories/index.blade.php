<div class="text-left">
    <div>Categories</div>
    <div class="mt-2 list-group list-group-flush">
        <div>
           <span class="list-group-item list-group-item-action @if(!$filteredCategory) active @endif"
                       wire:click="filterClearCategory()">
               All Categories
            </span>
        </div>
        @foreach($availableCategories as $category)
            @include('microweber-module-shop::livewire.shop.filters.categories.category-child', ['category' => $category])
        @endforeach
    </div>
</div>
