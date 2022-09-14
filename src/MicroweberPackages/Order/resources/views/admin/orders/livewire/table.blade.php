<div>

    @include('product::admin.product.livewire.table-includes.category-tree-js')
    @include('product::admin.product.livewire.table-includes.table-tr-reoder-js')

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
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.categoryTags"> Category & Tags</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shop"> Shop</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.other"> Other</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-admin-product-filters"  @if (empty($showFilters)) style="display: none"  @endif>
        <div class="container-filters p-3 pt-4 mb-4" style="background: rgb(236, 244, 255)">

            @if(isset($showFilters['categoryTags']) && $showFilters['categoryTags'])
            <div class="row">
            @include('product::admin.product.livewire.table-filters.category')
            @include('product::admin.product.livewire.table-filters.tags')
            </div>
            @endif

            @if(isset($showFilters['shop']) && $showFilters['shop'])
            <div class="row">
                @include('product::admin.product.livewire.table-filters.price-range')
                @include('product::admin.product.livewire.table-filters.stock-status')
                @include('product::admin.product.livewire.table-filters.discount')
                @include('product::admin.product.livewire.table-filters.sales')
                @include('product::admin.product.livewire.table-filters.quantity')
                @include('product::admin.product.livewire.table-filters.sku')
            </div>
            @endif

            @if(isset($showFilters['other']) && $showFilters['other'])
                <div class="row">
                    @include('product::admin.product.livewire.table-filters.visible')
                    @include('product::admin.product.livewire.table-filters.author')
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
                    <li><button class="dropdown-item" type="button" wire:click="multipleMoveToCategory">Move To Category</button></li>
                    <li><button class="dropdown-item" type="button" wire:click="multiplePublish">Publish</button></li>
                    <li><button class="dropdown-item" type="button" wire:click="multipleUnpublish">Unpublish</button></li>
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
                    <option value="price,desc">Price Desc</option>
                    <option value="price,asc">Price Asc</option>
                    <option value="sales,desc">Sales Desc</option>
                    <option value="sales,asc">Sales Asc</option>
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
                   <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.status"> Status</label>
                   <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.created_at"> Created At</label>
                   <label class="dropdown-item"><input type="checkbox" wire:model="showColumns.updated_at"> Updated At</label>
                </div>
            </div>
        </div>

        <div class="page-loading" wire:loading>
            Loading...
        </div>

    </div>

    <table class="table table-responsive" id="content-results-table">
        <thead>
        <tr>
            <th scope="col"> <input type="checkbox" wire:model="selectAll" class=""> </th>
            @if($showColumns['id'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['image'])
                <th scope="col">Image</th>
            @endif
            @if($showColumns['title'])
            <th scope="col">Title</th>
            @endif
            @if($showColumns['price'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'Price', 'key'=>'price', 'filters'=>$filters])
            @endif
            @if($showColumns['stock'])
            <th scope="col">Stock</th>
            @endif
            @if($showColumns['sales'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'Sales', 'key'=>'sales', 'filters'=>$filters])
            @endif
            @if($showColumns['quantity'])
            <th scope="col">Quantity</th>
            @endif
            @if($showColumns['author'])
            <th scope="col">Author</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)

        @php
        $orderUser = $order->user()->first();
        if ($order->customer_id > 0) {
            $orderUser = \MicroweberPackages\Customer\Models\Customer::where('id', $order->customer_id)->first();
        }

        $carts = $order->cart()->with('products')->get();
        $firstProduct = [];
        foreach ($carts as $cart) {
            if (isset($cart->products[0])) {
                $firstProduct = $cart->products[0];
                break;
            }
        }

        $item = $order->toArray();
        @endphp

        <tr class="manage-post-item">
            <td>
                <input type="checkbox" value="{{ $order->id }}"  class="js-select-posts-for-action"  wire:model="checked">
                <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                    <i class="mdi mdi-cursor-move"></i>
                </span>
            </td>
            @if($showColumns['id'])
                <td>
                    {{ $order->id }}
                </td>
            @endif

            @if($showColumns['image'])
            <td>
                <div class="img-circle-holder img-absolute">
                    <?php
                    $firstProductImage = '';
                    if (is_object($firstProduct) and is_object($firstProduct->media()->first())) {
                        $firstProductImage = $firstProduct->media()->first()->filename;
                    }
                    ?>
                    <?php if (!empty($firstProductImage)): ?>
                    <img src="<?php echo thumbnail($firstProductImage, 160, 160); ?>"/>
                    <?php else: ?>
                    <img src="<?php echo thumbnail(''); ?>"/>
                    <?php endif; ?>
                </div>
            </td>
            @endif

            @if($showColumns['title'])
            <td>
                @if (!empty($firstProduct['title']))
                <span class="text-primary text-break-line-2">{{$firstProduct['title']}}</span>
                @endif
            </td>
            @endif
            @if($showColumns['price'])
            <td>
               wfa
            </td>
            @endif
            @if($showColumns['stock'])
            <td>
              fwa
            </td>
            @endif
            @if($showColumns['sales'])
            <td style="text-align: center">
              fwa
            </td>
            @endif
            @if($showColumns['quantity'])
            <td style="text-align: center">
              wfa
            </td>
            @endif
            @if($showColumns['author'])
            <td>
                <?php if ($orderUser): ?>
                <small class="text-muted"><?php _e("Created by"); ?>:
                    <?php
                    if ($orderUser->first_name) {
                        echo $orderUser->first_name;
                        if ($orderUser->last_name) {
                            echo " " . $orderUser->last_name;
                        }
                    } else if ($orderUser) {
                        echo $orderUser->username;
                    }
                    ?>
                </small>
                <?php endif; ?>
            </td>
            @endif

        </tr>
        @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}

</div>


