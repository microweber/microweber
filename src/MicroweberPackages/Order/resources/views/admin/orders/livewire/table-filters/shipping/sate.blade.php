<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-user-filter" wire:ignore >
    <label class="d-block">
        Shipping Sate
    </label>

    <div class="mb-3 mb-md-0">
        @php
            $selectedItem = false;
            if (isset($filters['shipping.state'])) {
                $selectedItem = $filters['shipping.state'];
            }
        @endphp
        @livewire('admin-orders-shipping-state-autocomplete', ['selectedItem'=>$selectedItem])
    </div>
</div>
