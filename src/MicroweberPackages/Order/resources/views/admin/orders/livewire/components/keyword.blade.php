<div class="keyword-blade-filters-menu input-icon col-xl-12 col-lg-5 col ms-auto">
    <div class="input-group input-group-flat ">
        <input type="text" wire:model.debounce.500ms="filters.keyword" placeholder="<?php _e("Search by keyword"); ?>..." class="form-control" autocomplete="off">
        <span class="input-group-text">
             <a href="#" class="link-secondary ms-2" data-bs-toggle="dropdown" aria-expanded="false" ><!-- Download SVG icon from http://tabler-icons.io/i/adjustments -->
                <svg xmlns="http://www.w3.org/2000/svg" data-bs-toggle="tooltip" aria-label="Search settings" data-bs-original-title="Search settings" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M6 4v4"></path><path d="M6 12v8"></path><path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M12 4v10"></path><path d="M12 18v2"></path><path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M18 4v1"></path><path d="M18 9v11"></path></svg>
            </a>
            <div class="dropdown-menu p-3">
        <h6 class="dropdown-header">Order</h6>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.id"> Id</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.orderStatus"> Order Status
        </label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.isPaid"> Payment Status</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.amountBetween"> Amount Range</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.productId"> Product </label>

        <h6 class="dropdown-header">Shipping</h6>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingService"> Shipping Service</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingTrackingNumber">
            Shipping Tracking Number</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingStatus"> Shipping
            Status</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippedAt"> Shipped
            at</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingCountry"> Shipping
            Country</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingCity"> Shipping City</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingSate"> Shipping Sate</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingPostCode"> Shipping
            Post Code</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingAddress"> Shipping
            Address</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingPhone"> Shipping
            Phone</label>

        <h6 class="dropdown-header">Customer</h6>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.customer"> Customer</label>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.userId"> Registered
            User </label>

        <h6 class="dropdown-header">Date</h6>
        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween"> Orders Date
            Range
        </label>
    </div>
        </span>
    </div>
</div>

