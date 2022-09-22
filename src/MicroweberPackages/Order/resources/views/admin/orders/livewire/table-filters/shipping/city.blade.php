<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-user-filter" wire:ignore >
    <label class="d-block">
        Shipping City
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItem = false;
            if (isset($filters['shipping']['city'])) {
                $selectedItem = $filters['shipping']['city'];
            }
        @endphp
        @livewire('admin-orders-shipping-city-autocomplete', ['selectedItem'=>$selectedItem])
    </div>
</div>
