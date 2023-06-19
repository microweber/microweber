<div class="col-xxl-10 col-lg-11 col-12 mx-auto">

    <div class="card-body col-xl-12 mx-auto mb-3">
        <div class="card-header d-flex col-12 align-items-center justify-content-between">
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
                <h1 class="main-pages-title">{{_e('Orders')}}</h1>
            </div>
            @include('order::admin.orders.livewire.components.keyword')
            &nbsp;&nbsp;
            @include('order::admin.orders.action-links-top')
        </div>
    </div>

    <div class="no-items-found orders" style="display:none">
        <div class="row">
            <div class="col-12">
                <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                    <h4>You don’t have any orders yet</h4>
                    <p>Here you can track your orders</p>
                    <br/>
                    <a href="javascript:mw_admin_add_order_popup()" class="btn btn-primary btn-rounded">{{_e('Add Order')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            <a href="#" class="link-secondary ms-2 " data-bs-toggle="dropdown" aria-expanded="false" ><!-- Download SVG icon from http://tabler-icons.io/i/adjustments -->
                <svg xmlns="http://www.w3.org/2000/svg" data-bs-toggle="tooltip" aria-label="Search settings" data-bs-original-title="Search settings" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M6 4v4"></path><path d="M6 12v8"></path><path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M12 4v10"></path><path d="M12 18v2"></path><path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M18 4v1"></path><path d="M18 9v11"></path></svg>
                Show Filters
            </a>

            <div class="dropdown-menu p-3" style="max-height:300px;overflow-y: scroll;padding-bottom:10px">
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
                    Range</label>
            </div>

        </div>
    </div>

    <div class="d-flex flex-wrap mt-3 mb-3">

        @if(isset($showFilters['id']) && $showFilters['id'])
            @include('order::admin.orders.livewire.table-filters.order_id')
        @endif

        @if(isset($showFilters['orderStatus']) && $showFilters['orderStatus'])
            @include('order::admin.orders.livewire.table-filters.order_status')
        @endif

        @if(isset($showFilters['isPaid']) && $showFilters['isPaid'])
            @include('order::admin.orders.livewire.table-filters.payment_status')
        @endif

        @if(isset($showFilters['amountBetween']) && $showFilters['amountBetween'])
            @include('order::admin.orders.livewire.table-filters.amount_range')
        @endif

        @if(isset($showFilters['productId']) && $showFilters['productId'])
            @include('order::admin.orders.livewire.table-filters.product')
        @endif

        @if(isset($showFilters['shippingService']) && $showFilters['shippingService'])
            @include('order::admin.orders.livewire.table-filters.shipping.service')
        @endif

        @if(isset($showFilters['shippingTrackingNumber']) && $showFilters['shippingTrackingNumber'])
            @include('order::admin.orders.livewire.table-filters.shipping.tracking_number')
        @endif

        @if(isset($showFilters['shippingStatus']) && $showFilters['shippingStatus'])
            @include('order::admin.orders.livewire.table-filters.shipping.status')
        @endif

        @if(isset($showFilters['shippingShippedAt']) && $showFilters['shippingShippedAt'])
            @include('order::admin.orders.livewire.table-filters.shipping.shipped_at')
        @endif

        @if(isset($showFilters['shippingCountry']) && $showFilters['shippingCountry'])
            @include('order::admin.orders.livewire.table-filters.shipping.country')
        @endif

        @if(isset($showFilters['shippingCity']) && $showFilters['shippingCity'])
            @include('order::admin.orders.livewire.table-filters.shipping.city')
        @endif

        @if(isset($showFilters['shippingState']) && $showFilters['shippingState'])
            @include('order::admin.orders.livewire.table-filters.shipping.sate')
        @endif

        @if(isset($showFilters['shippingPostCode']) && $showFilters['shippingPostCode'])
            @include('order::admin.orders.livewire.table-filters.shipping.post-code')
        @endif

        @if(isset($showFilters['shippingAddress']) && $showFilters['shippingAddress'])
            @include('order::admin.orders.livewire.table-filters.shipping.address')
        @endif

        @if(isset($showFilters['shippingPhone']) && $showFilters['shippingPhone'])
            @include('order::admin.orders.livewire.table-filters.shipping.phone')
        @endif

        @if(isset($showFilters['customer']) && $showFilters['customer'])
            @include('order::admin.orders.livewire.table-filters.customer')
        @endif

        @if(isset($showFilters['userId']) && $showFilters['userId'])
            @include('order::admin.orders.livewire.table-filters.user')
        @endif

        @if(isset($showFilters['dateBetween']) && $showFilters['dateBetween'])
            @include('order::admin.orders.livewire.table-filters.date_range')
        @endif
    </div>

    @livewire('admin-orders-table', ['filters'=>$filters])

</div>
