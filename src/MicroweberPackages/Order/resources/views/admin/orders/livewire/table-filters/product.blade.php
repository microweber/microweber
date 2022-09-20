<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-product-filter">
    <label class="d-block">
        Product
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItem = false;
            if (isset($filters['productId'])) {
                $selectedItem = $filters['productId'];
            }
        @endphp
        @livewire('admin-products-autocomplete', ['selectedItem'=>$selectedItem])
    </div>

</div>
