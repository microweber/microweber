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

            @if(isset($showFilters['order']) && $showFilters['order'])
            <div class="row">
                @include('order::admin.orders.livewire.table-filters.visible')
            </div>
            @endif

            @if(isset($showFilters['customer']) && $showFilters['customer'])
                <div class="row">
                    @include('order::admin.orders.livewire.table-filters.customer')
                </div>
            @endif

            @if(isset($showFilters['date']) && $showFilters['date'])
                <div class="row">
                    @include('product::admin.product.livewire.table-filters.date')
                </div>
            @endif
        </div>
    </div>


    <div style="height: 60px" class="bulk-actions-show-columns">

        @if(count($checked) > 0)

            @if (count($checked) == count($products->items()))
                <div class="col-md-10 mb-2">
                    You have selected all {{ count($checked) }} items.
                    <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll">Deselect All</button>
                </div>
            @else
            <div>
                You have selected {{ count($checked) }} items,
                Do you want to Select All {{ count($products->items()) }}?
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

        <div class="page-loading" wire:loading>
            Loading...
        </div>

    </div>

    <table class="table table-responsive">
        <thead>
        <tr>
            <th scope="col"> <input type="checkbox" wire:model="selectAll" class=""> </th>
            @if($showColumns['id'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['products'])
                <th scope="col">Products</th>
            @endif


            @if($showColumns['customer'])
            <th scope="col">Customer</th>
            @endif

            @if($showColumns['shipping_method'])
            <th scope="col">Shipping Method</th>
            @endif

            @if($showColumns['payment_method'])
            <th scope="col">Payment Method</th>
            @endif

            @if($showColumns['payment_status'])
                <th scope="col">Payment Status</th>
            @endif


            @if($showColumns['total_amount'])
                <th scope="col">Total Amount</th>
            @endif

            @if($showColumns['status'])
                <th scope="col">Status</th>
            @endif

              @if($showColumns['created_at'])
            <th scope="col">Created At</th>
            @endif
            @if($showColumns['updated_at'])
                <th scope="col">Updated At</th>
            @endif

            @if($showColumns['actions'])
                <th scope="col">Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)

        <tr class="manage-post-item">
            <td>
                <input type="checkbox" value="{{ $order->id }}" wire:model="checked">
            </td>
            @if($showColumns['id'])
                <td>
                    {{ $order->id }}
                </td>
            @endif

            @if($showColumns['products'])
            <td>
                @php
                    $carts = $order->cart()->with('products')->get();
                @endphp
                @foreach ($carts as $cart)
                   <a href="#">{{$cart->title}}</a> <span class="text-muted">x{{$cart->qty}}</span> <br />
                @endforeach
            </td>
            @endif

            @if($showColumns['customer'])
            <td>
                {{$order->customerName()}}
            </td>
            @endif

            @if($showColumns['shipping_method'])
            <td>
                {{$order->shippingMethodName()}}
            </td>
            @endif
            @if($showColumns['payment_method'])
            <td style="text-align: center">
                {{$order->payment_gw}}
            </td>
            @endif
            @if($showColumns['payment_status'])
                <td style="text-align: center">
                    @if($order->is_paid == 1)
                        <span class="badge badge-success">Paid</span>
                    @else
                        <span class="badge badge-danger">Unpaid</span>
                    @endif
                </td>
            @endif


            @if($showColumns['total_amount'])
                <td>
                    <span class="badge badge-success">{{$order->payment_amount}} {{$order->payment_currency}}</span>
                </td>
            @endif

            @if($showColumns['status'])
                <td style="text-align: center">
                    @if($order->order_status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($order->order_status == 'new')
                        <span class="badge badge-primary">New</span>
                    @else
                        <span class="badge badge-primary">{{$order->order_status}}</span>
                    @endif
                </td>
            @endif

              @if($showColumns['created_at'])
            <td style="text-align: center">
                {{$order->created_at}}
            </td>
            @endif
              @if($showColumns['updated_at'])
            <td style="text-align: center">
                {{$order->updated_at}}
            </td>
            @endif

            @if($showColumns['actions'])
                <td style="text-align: center">
                    <a href="{{route('admin.order.show', $order->id)}}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-eye"></i>    View
                    </a>
                </td>
            @endif

        </tr>
        @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}

</div>


