<div>
    @if(!empty($appliedFiltersFriendlyNames))
        <div class="mb-4">
            <b>Filters</b> <br />
            @foreach($appliedFiltersFriendlyNames as $filterKey=>$filterValues)
                <span class="btn btn-sm btn-outline-primary">
                  <i class="mw-icon-category"></i>
                  <span class="tag-label-content">
                       {{ucfirst($filterKey)}}:
                      @if(is_array($filterValues))
                          {{implode(', ', $filterValues)}}
                      @endif
                      @if(is_string($filterValues))
                          {{$filterValues}}
                      @endif
                  </span>
                  <span class="mw-icon-close ml-1" wire:click="removeFilter('{{$filterKey}}')"></span>
              </span>
            @endforeach

            <button class="btn btn-outline-danger btn-sm" wire:click="clearFilters">Clear filers</button>
        </div>
    @endif

    <div class="d-flex">
        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            <input wire:model.debounce.500ms="filters.keyword" type="text" placeholder="Search by keyword..."
                   class="form-control">
        </div>
        <div class="ms-0 ms-md-2 mb-3 mb-md-0">
            `<button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown" aria-expanded="false">
                More filters &nbsp; <i class="fa fa-plus-circle"></i>
            </button>
            <div class="dropdown-menu p-3" style="max-height:300px;overflow-y: scroll;padding-bottom:10px">
                <h6 class="dropdown-header">Order</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.id"> Id</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.orderStatus"> Order Status </label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.paymentStatus"> Payment Status</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.orderAmountRange"> Order Amount Range</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.product">  Product </label>

                <h6 class="dropdown-header">Shipping</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingType"> Shipping Type</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingTrackingNumber"> Shipping Tracking Number</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingStatus"> Shipping Status</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippedAt"> Shipped at</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingCountry">  Shipping Country</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingCity">  Shipping City</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingSate">  Shipping Sate</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingPostCode">  Shipping Post Code</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingAddress">  Shipping Address</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shippingPhone">  Shipping Phone</label>

                <h6 class="dropdown-header">Customer</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.customer"> Customer</label>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.registeredUser">  Registered User </label>

                <h6 class="dropdown-header">Date</h6>
                <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.dateBetween">  Orders Date Range</label>
            </div>`
        </div>
    </div>

    <div class="d-flex flex-wrap mt-3">

            @if(isset($showFilters['category']) && $showFilters['category'])
                @include('order::admin.orders.livewire.table-filters.order_id')
            @endif

            @include('order::admin.orders.livewire.table-filters.order_status')
            @include('order::admin.orders.livewire.table-filters.payment_status')
            @include('order::admin.orders.livewire.table-filters.amount_range')
            @include('order::admin.orders.livewire.table-filters.product')

            @include('order::admin.orders.livewire.table-filters.shipping.service')
            @include('order::admin.orders.livewire.table-filters.shipping.tracking_number')
            @include('order::admin.orders.livewire.table-filters.shipping.status')
            @include('order::admin.orders.livewire.table-filters.shipping.shipped_at')
            @include('order::admin.orders.livewire.table-filters.shipping.country')
            @include('order::admin.orders.livewire.table-filters.shipping.city')
            @include('order::admin.orders.livewire.table-filters.shipping.sate')
            @include('order::admin.orders.livewire.table-filters.shipping.post-code')
            @include('order::admin.orders.livewire.table-filters.shipping.address')
            @include('order::admin.orders.livewire.table-filters.shipping.phone')

            @include('order::admin.orders.livewire.table-filters.customer')
            @include('order::admin.orders.livewire.table-filters.user')

            @include('order::admin.orders.livewire.table-filters.date_range')
    </div>

    @livewire('admin-orders-table', ['filters'=>$filters])

</div>
