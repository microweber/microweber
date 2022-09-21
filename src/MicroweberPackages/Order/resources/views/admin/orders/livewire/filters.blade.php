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

    <div class="d-md-flex justify-content-between mb-3">
        <div class="d-md-flex">
            <div class="mb-3 mb-md-0 input-group">
                <input wire:model.stop="filters.keyword" type="text" placeholder="Search by keyword..."
                       class="form-control">
            </div>
            <div class="ms-0 ms-md-2 mb-3 mb-md-0">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                       <i class="fa fa-filter"></i> Filters
                    </button>
                    <div class="dropdown-menu p-3">
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.order"> Order</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shipping"> Shipping</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.customer"> Customer</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.date"> Date</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-admin-product-filters"  @if (empty($showFilters)) style="display: none"  @endif>
        <div class="container-filters p-3 pt-4 mb-4" style="background: rgb(236, 244, 255)">

            <div class="row js-row-order-filters-box" style="@if(!isset($showFilters['order']) || !$showFilters['order']) display:none; @endif">
                @include('order::admin.orders.livewire.table-filters.order_id')
                @include('order::admin.orders.livewire.table-filters.order_status')
                @include('order::admin.orders.livewire.table-filters.payment_status')
                @include('order::admin.orders.livewire.table-filters.amount_range')
                @include('order::admin.orders.livewire.table-filters.product')
            </div>

            <div class="row js-row-date-filters-box" style="@if(!isset($showFilters['shipping']) || !$showFilters['shipping']) display:none; @endif">
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
            </div>

            <div class="row js-row-customer-filters-box" style="@if(!isset($showFilters['customer']) || !$showFilters['customer']) display:none; @endif">
                @include('order::admin.orders.livewire.table-filters.customer')
                @include('order::admin.orders.livewire.table-filters.user')
            </div>

            <div class="row js-row-date-filters-box" style="@if(!isset($showFilters['date']) || !$showFilters['date']) display:none; @endif">
                @include('order::admin.orders.livewire.table-filters.date_range')
            </div>

        </div>
    </div>

    @livewire('admin-orders-table', ['filters'=>$filters])

</div>
