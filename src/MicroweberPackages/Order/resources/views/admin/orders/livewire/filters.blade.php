<div class="col-xxl-10 col-lg-11 col-12 mx-auto">

    <div class="card-body col-xl-12 mx-auto mb-3">
        <div class="card-header d-flex flex-wrap col-12 align-items-center justify-content-between">
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0 d-md-block d-none">
                <h1 class="main-pages-title mb-0 ">{{_e('Orders')}}</h1>
            </div>

            <div class="col-auto my-lg-0 my-3 me-md-3 col-lg-5 ms-auto">
                @include('order::admin.orders.livewire.components.keyword')
            </div>

           @include('order::admin.orders.action-links-top')
        </div>
    </div>

    <div class="no-items-found orders" style="display:none">
        <div class="row">
            <div class="col-12">
                <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                    <h4>{{_e('You don’t have any orders yet')}}</h4>
                    <p>{{_e('Here you can track your orders')}}</p>
                    <br/>
                    <a href="javascript:mw_admin_add_order_popup()" class="btn btn-primary btn-rounded">{{_e('Add Order')}}</a>
                </div>
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
