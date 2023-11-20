<div class="mt-4 text-left">
    <div>Categories</div>
    <ul class="mt-2 list-group list-group-flush">
        @foreach($availableCategories as $category)
            @include('microweber-module-shop::livewire.shop.filters.categories.category-child', ['category' => $category])
        @endforeach
    </ul>
</div>
