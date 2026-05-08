<div class="text-left mw-shop-filter-categories">
    {{-- AI-67 / TICKET-ZZ (cycle-74 2026-05-08): heading semantics.
         Was a bare `<div>Categories</div>` — screen-reader users
         couldn't jump to filter sections via heading navigation.
         h3 chosen because the page-level h1/h2 are reserved for
         the page title + product list landmark. --}}
    <h3 class="mw-shop-filter-heading h6 mb-2">{{ __('Categories') }}</h3>
    <div class="mt-2 list-group list-group-flush" role="list">
        {{-- AI-67 / TICKET-ZZ (cycle-74): was a click-only <span> —
             keyboard users couldn't activate it (no tabindex, no
             space/enter handler, no role=button). Real <button>
             gets all that for free + Bootstrap's btn-reset wins
             us back the list-group visual styling without losing
             the native button semantics that AT depends on. --}}
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
