<div class="d-flex justify-content-between flex-wrap mt-3">
    <div class="col-12 col-md-2">
        @if(!empty($availableCategories) && !$filterSettings['disable_categories_filtering'])
            @include('modules.shop::livewire.shop.filters.categories.index')
        @endif

        @if(!$filterSettings['disable_price_range_filtering'])
            @include('modules.shop::livewire.shop.filters.price_range.index')
        @endif

        @if(!$filterSettings['disable_offers_filtering'])
            @include('modules.shop::livewire.shop.filters.offers.index')
        @endif

        @if(!empty($availableCustomFields) && !$filterSettings['disable_custom_fields_filtering'])
            @include('modules.shop::livewire.shop.filters.custom_fields.index')
        @endif

        @if(!empty($availableTags) && !$filterSettings['disable_tags_filtering'])
            @include('modules.shop::livewire.shop.filters.tags.index')
        @endif
    </div>
    <div class="col-12 col-md-9">
        @include('modules.shop::livewire.shop.filters.top.index')

        {{-- aria-live="polite" announces grid updates to screen readers when filters
             or search change the visible products; wire:loading.attr toggles
             aria-busy="true" while Livewire is fetching so SR users hear
             "loading" instead of stale results. --}}
        <div class="row mt-4" aria-live="polite" wire:loading.attr="aria-busy">
            {{-- AI-286: 3-col (lg/xl) → 2-col (md) → 1-col (sm) responsive
                 product grid. Previously `col-12 col-lg-6 col-xl-6` produced
                 a 1→2 jump with no intermediate 2-col state at tablet widths
                 and stopped at 2 cols on wide desktop. Updated breakpoints
                 land cleanly on the AI-286 test viewports
                 (320/390/768/1024/1440). --}}
            @forelse($products as $product)
                <div class="col-12 col-md-6 col-lg-4 col-xl-4 mb-5">
                    @include('modules.shop::livewire.shop.product-card')
                </div>
            @empty
                <div class="col-12">
                    <div class="mw-shop-empty-state text-center py-5 px-3">
                        <svg class="mw-shop-empty-state__illustration mb-4"
                             xmlns="http://www.w3.org/2000/svg"
                             width="160" height="160" viewBox="0 0 160 160"
                             fill="none" aria-hidden="true" role="img">
                            <circle cx="80" cy="80" r="76" fill="#f8f9fa" stroke="#d1d5db" stroke-width="2"/>
                            <path d="M48 64 L48 56 C48 49 53 44 60 44 L100 44 C107 44 112 49 112 56 L112 64"
                                  stroke="#9ca3af" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <path d="M40 64 L120 64 L114 116 C113 121 109 124 104 124 L56 124 C51 124 47 121 46 116 Z"
                                  fill="#ffffff" stroke="#9ca3af" stroke-width="3" stroke-linejoin="round"/>
                            <line x1="64" y1="80" x2="64" y2="100" stroke="#9ca3af" stroke-width="3" stroke-linecap="round"/>
                            <line x1="96" y1="80" x2="96" y2="100" stroke="#9ca3af" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        <h3 class="mw-shop-empty-state__title h4 mb-2">
                            {{ _e('No products found', true) }}
                        </h3>
                        <p class="mw-shop-empty-state__body text-muted mb-4">
                            {{ _e('Try clearing the filters or searching for something else.', true) }}
                        </p>
                        <button type="button"
                                class="btn btn-primary mw-shop-empty-state__reset"
                                wire:click="resetFilters">
                            {{ _e('Clear all filters', true) }}
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        @if(!$products->isEmpty())
            <div class="d-flex justify-content-center mb-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
