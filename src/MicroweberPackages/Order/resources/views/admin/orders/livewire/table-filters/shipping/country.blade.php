<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-user-filter" wire:ignore >
    <label class="d-block">
        Shipping Country
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItem = false;
            if (isset($filters['shipping.country'])) {
                $selectedItem = $filters['shipping.country'];
            }
        @endphp
        @livewire('admin-orders-shipping-country-autocomplete', ['selectedItem'=>$selectedItem])
    </div>
</div>
