<div class="d-flex justify-content-between flex-wrap mt-3">
    <div class="col-md-2">
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
    <div class="col-md-9">
        @include('modules.shop::livewire.shop.filters.top.index')

        <div class="row mt-7">
            @foreach($products as $product)
                <div class="col-xl-6 col-lg-6 col-sm-12 mb-5">
                    @include('modules.shop::livewire.shop.product-card-skin-1')
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mb-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
