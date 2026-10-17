<div class="text-left mw-shop-filter-categories">

    <h3 class="mw-shop-filter-heading h6 mb-2">{{ __('Categories') }}</h3>
    <div class="mt-2 list-group list-group-flush" role="list">

        <button type="button"
                role="listitem"
                class="list-group-item list-group-item-action @if(!$filteredCategory) active @endif"
                @if(!$filteredCategory) aria-current="true" @endif
                wire:click="filterClearCategory()">
            {{ __('All Categories') }}
        </button>
        @foreach($availableCategories as $category)
            @include('modules.shop::livewire.shop.filters.categories.category-child', ['category' => $category])
        @endforeach
    </div>
</div>
