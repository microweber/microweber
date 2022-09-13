<div>

    {!! json_encode($filters, JSON_PRETTY_PRINT) !!}
    <br />
    <br />
    <br />
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
                  <span class="mw-icon-close ml-1"></span>
              </span>
        @endforeach


            <button class="btn btn-outline-danger btn-sm" wire:click="clearFilters">Clear filers</button>
        </div>
    @endif

    <div id="js-admin-product-filters">

        <div class="container-filters p-3 pt-4 mb-4" style="background: rgb(236, 244, 255)">
            <div class="row">

                @include('product::admin.product.livewire.table-filters.category')
                @include('product::admin.product.livewire.table-filters.tags')
                @include('product::admin.product.livewire.table-filters.price-range')
                @include('product::admin.product.livewire.table-filters.stock-status')
                @include('product::admin.product.livewire.table-filters.discount')
                @include('product::admin.product.livewire.table-filters.sales')
                @include('product::admin.product.livewire.table-filters.quantity')
                @include('product::admin.product.livewire.table-filters.sku')
                @include('product::admin.product.livewire.table-filters.visible')
                @include('product::admin.product.livewire.table-filters.date')

            </div>
        </div>

        <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
            <label>Keyword</label>
            <div class="mb-3 mb-md-0 input-group">
                <input wire:model.stop="filters.keyword" type="text" placeholder="Search by keyword..." class="form-control">
            </div>
        </div>  <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
            <label>orderBy</label>
            <select wire:model.stop="filters.orderBy" class="form-control">
                <option value="">Any</option>
                <option value="id,desc">id,desc</option>
                <option value="id,asc">id,asc</option>
                <option value="price,desc">price,desc</option>
                <option value="price,asc">price,asc</option>
                <option value="sales,desc">sales,desc</option>
                <option value="sales,asc">sales,asc</option>
            </select>
        </div>
    </div>

<div style="height: 60px">

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
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Show columns
            </button>
            <div class="dropdown-menu p-3">
               <label><input type="checkbox" wire:model="showColumns.image"> Image</label>
               <label><input type="checkbox" wire:model="showColumns.title"> Title</label>
               <label><input type="checkbox" wire:model="showColumns.price"> Price</label>
               <label><input type="checkbox" wire:model="showColumns.stock"> Stock</label>
               <label><input type="checkbox" wire:model="showColumns.sales"> Sales</label>
               <label><input type="checkbox" wire:model="showColumns.quantity"> Quantity</label>
               <label><input type="checkbox" wire:model="showColumns.author"> Author</label>
            </div>
        </div>
    </div>

    <div class="page-loading" wire:loading>
        Loading...
    </div>

</div>

    <table class="table">
        <thead>
        <tr>
            <th scope="col"> <input type="checkbox" wire:model="selectAll"> </th>
            @if($showColumns['image'])
            <th scope="col">Image</th>
            @endif
            @if($showColumns['title'])
            <th scope="col">Title</th>
            @endif
            @if($showColumns['price'])
            <th scope="col">Price</th>
            @endif
            @if($showColumns['stock'])
            <th scope="col">Stock</th>
            @endif
            @if($showColumns['sales'])
            <th scope="col">Sales</th>
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
        @foreach ($products as $product)
        <tr>
            <td>
                <input type="checkbox" value="{{ $product->id }}" wire:model="checked">
                &nbsp; &nbsp; <span class="text-muted">{{ $product->id }}</span>
            </td>
            @if($showColumns['image'])
            <td>
                <img src="{{$product->thumbnail()}}" class="w-8 h-8 rounded-full">
            </td>
            @endif
            @if($showColumns['title'])
            <td>
                <div class="manage-item-main-top">

                    <a target="_self" href="" class="btn btn-link p-0">
                        <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                            {{$product->title}}
                        </h5>
                    </a>
                    @if($product->categories->count() > 0)
                        <span class="manage-post-item-cats-inline-list">
                        @foreach($product->categories as $category)
                                @if($category->parent)
                                    <a href="#" class="btn btn-link p-0 text-muted">{{$category->parent->title}}</a>
                                @endif
                            @endforeach
                         </span>
                    @endif
                    <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self"
                       href="{{$product->link()}}">
                        <small class="text-muted">{{$product->link()}}</small>
                    </a>
                 </div>


                <div class="manage-post-item-links mt-3">
                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-success btn-sm">Live Edit</a>
                    <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-outline-danger btn-sm">Delete</a>
                    @if ($product->is_active < 1)
                    <a href="{{route('admin.product.edit', $product->id)}}" class="badge badge-warning font-weight-normal">Unpublished</a>
                    @endif
                </div>


            </td>
            @endif
            @if($showColumns['price'])
            <td>
                @php
                if ($product->hasSpecialPrice()) {
                    $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($product->price).'</span>';
                    $price .= '<br /><span class="h5">'.currency_format($product->specialPrice).'</span>';
                } else {
                    $price = '<span class="h5">'.currency_format($product->price).'</span>';
                }
                @endphp

                {!! $price !!}
            </td>
            @endif
            @if($showColumns['stock'])
            <td>
                @php
                  if ($product->InStock) {
                    $stock = '<span class="badge badge-success badge-sm">In stock</span>';
                } else {
                    $stock = '<span class="badge badge-danger badge-sm">Out Of Stock</span>';
                }
                @endphp
                {!! $stock !!}
            </td>
            @endif
            @if($showColumns['sales'])
            <td>
                @php
                $ordersUrl = route('admin.order.index') . '?productId='.$product->id;
                if ($product->salesCount == 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->salesCount.' sale</span></a>';
                } else if ($product->salesCount > 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->salesCount.' sales</span></a>';
                } else {
                    $sales = '<span>'.$product->salesCount.' sales</span>';
                }
                @endphp
                {!! $sales !!}
            </td>
            @endif
            @if($showColumns['quantity'])
            <td>
            @php
                if ($product->qty == 'nolimit') {
                      $quantity = '<i class="fa fa-infinity" title="Unlimited Quantity"></i>';
                  } else if ($product->qty == 0) {
                      $quantity = '<span class="text-small text-danger">0</span>';
                  } else {
                      $quantity = $product->qty;
                  }
            @endphp
                {!! $quantity !!}
            </td>
            @endif
            @if($showColumns['author'])
            <td>
                {{$product->authorName()}}
            </td>
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>

    {{ $products->links() }}

</div>


