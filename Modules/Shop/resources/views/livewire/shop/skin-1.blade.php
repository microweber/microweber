<div class="d-flex justify-content-between flex-wrap mt-3">
    {{-- task-2026-05-17-328124 / AI-862 — sibling skin fix. Same Bootstrap
         grid math defect (col-12 col-md-2 + col-12 col-md-9 = 11 cols at md
         with 116px sidebar legibility cliff). See default.blade.php for the
         full Option A2 rationale (stack-at-md, sidebar-at-lg+). --}}
    <div class="col-12 col-lg-3">
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
    <div class="col-12 col-lg-9">
        @include('modules.shop::livewire.shop.filters.top.index')

        {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB finding #9 (a11y half):
             aria-live + wire:loading.attr=aria-busy on the products grid;
             see default.blade.php for the full note. --}}
        <div class="row mt-7" aria-live="polite" wire:loading.attr="aria-busy">
            @foreach($products as $product)
                <div class="col-12 col-lg-6 col-xl-6 mb-5">
                    @include('modules.shop::livewire.shop.product-card-skin-1')
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mb-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
