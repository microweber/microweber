<div>

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
                        <li><button class="dropdown-item" type="button" wire:click="showDeleteModal">Delete</button></li>
                        <li><button class="dropdown-item" type="button" wire:click="showPaymentStatusModal">Change Payment Status</button></li>
                        <li><button class="dropdown-item" type="button" wire:click="showStatusModal">Change Order Status</button></li>
                    </ul>
                </div>
            </div>
        @endif

        @if($deleteModal)
            <div class="js-admin-orders-bulk-delete-modal">
            <script>
                mw.tools.confirm("Are you sure you want to delete the selected data?", function () {
                    window.livewire.emit('deleteExecute');
                }, function () {
                    window.livewire.emit('hideDeleteModal');
                });
            </script>
            </div>
        @endif

        @if($statusModal)
            <script>
                $(document).ready(function () {
                    var dialog = mw.dialog({
                        content: $('#js-bulk-change-status').html(),
                        title: 'Order Status',
                        height: 'auto',
                    });
                });
            </script>
            <template id="js-bulk-change-status">
                Order Status
                <select class="form-control" wire:model="bulkStatus">
                    <option value="1">New</option>
                    <option value="0">Pending</option>
                </select>
                <button type="button" class="btn btn-success mt-3" wire:click="statusExecute">Change</button>
            </template>
        @endif

        @if($paymentStatusModal)
            <script>
                $(document).ready(function () {
                    var dialog = mw.dialog({
                        content: $('#js-bulk-change-payment-status').html(),
                        title: 'Payment Status',
                        height: 'auto'
                    });
                    $('.js-bulk-change-payment-status-change').click(function () {
                        var status = $('.js-bulk-change-payment-status-select').val();
                        window.livewire.emit('paymentStatusExecute', status);
                    });
                });
            </script>
            <template id="js-bulk-change-payment-status">
                <div>
                Payment Status
                <select class="form-control js-bulk-change-payment-status-select">
                    <option value="1">Paid</option>
                    <option value="0">Unpaid</option>
                </select>
                <button type="button" class="btn btn-success mt-3 js-bulk-change-payment-status-change">Change</button>
                </div>
            </template>
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
                <select class="form-control form-control-sm" wire:model="paginationLimit">
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

    <div class="page-loading" wire:loading>
        Loading...
    </div>

    <table class="table table-responsive">
        <thead>
        <tr>
            <th scope="col"> <input type="checkbox" wire:model="selectAll" class=""> </th>
            @if($showColumns['id'])
                @include('order::admin.orders.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
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
                {{$order->paymentMethodName()}}
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
                    <span class="badge badge-success">{{number_format($order->payment_amount,2)}} {{$order->payment_currency}}</span>
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

        <tfoot>
        <tr>
            @if($showColumns['total_amount'] && $orders->total() > 0)
                <td colspan="7">
                  <span class="text-muted">{{ $orders->total() }} results found</span>
                </td>
                <td>
                    @php
                    $paymentCurrency = get_currency_symbol();
                    $totalAmountOfOrders = 0;
                    foreach ($orders as $order) {
                        $totalAmountOfOrders = $totalAmountOfOrders + $order->payment_amount;
                        $paymentCurrency = $order->payment_currency;
                    }
                    @endphp

                    <span class="badge badge-success">
                        {{number_format($totalAmountOfOrders, 2)}} {{$paymentCurrency}}
                    </span>
                </td>
                <td colspan="2"></td>
            @endif
        </tr>
        </tfoot>

    </table>

    {{ $orders->links() }}

</div>


