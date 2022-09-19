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
                        Filters
                    </button>
                    <div class="dropdown-menu p-3">
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.order"> Order</label>
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

            <div class="row js-row-customer-filters-box" style="@if(!isset($showFilters['customer']) || !$showFilters['customer']) display:none; @endif">
                @include('order::admin.orders.livewire.table-filters.customer')
                @include('order::admin.orders.livewire.table-filters.user')
            </div>

            <div class="row js-row-date-filters-box" style="@if(!isset($showFilters['date']) || !$showFilters['date']) display:none; @endif">
                @include('order::admin.orders.livewire.table-filters.date_range')
            </div>

        </div>
    </div>

    <div style="height: 60px" class="bulk-actions-show-columns">

        @if(count($checked) > 0)

            @if (count($checked) == count($orders->items()))
                <div class="col-md-10 mb-2">
                    You have selected all {{ count($checked) }} items.
                    <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                </div>
            @else
                <div>
                    You have selected {{ count($checked) }} items,
                    Do you want to Select All {{ count($orders->items()) }}?
                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="selectAll">Select All</button>
                </div>
            @endif
        @endif

        @if(count($checked) > 0)
            <div class="pull-left">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" type="button" wire:click="multipleDelete">Delete</button></li>
                    </ul>
                </div>
            </div>
        @endif

        <div class="pull-right">

            <div class="d-inline-block mx-1">

                <span class="d-md-block d-none">Sort</span>
                <select wire:model.stop="filters.orderBy" class="form-control form-control-sm">
                    <option value="">Any</option>
                    <option value="id,desc">Id Desc</option>
                    <option value="id,asc">Id Asc</option>
                </select>
            </div>

            <div class="d-inline-block mx-1">

                <span class="d-md-block d-none">Limit</span>
                <select class="form-control form-control-sm" wire:model="paginate">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Show columns
                </button>
                <div class="dropdown-menu p-3">
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.id"> Id</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.products"> Products</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.customer"> Customer</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.total_amount"> Total Amount</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.shipping_method"> Shipping Method</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.payment_method"> Payment Method</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.payment_status"> Payment Status</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.status"> Status</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.created_at"> Created At</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.updated_at"> Updated At</label>
                    <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.actions"> Actions</label>
                </div>
            </div>
        </div>

    </div>

    @livewire('admin-orders-table', ['filters'=>$filters,'showColumns'=>$showColumns])

</div>
