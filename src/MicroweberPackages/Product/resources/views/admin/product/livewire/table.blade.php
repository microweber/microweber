<div>

    {!! json_encode($filters, JSON_PRETTY_PRINT) !!}

    <button class="btn btn-outline-danger" wire:click="clearFilters">Clear filers</button>

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

    <div class="page-loading" wire:loading>
        Loading...
    </div>

    <table class="table">
        <thead>
        <tr>
            <th scope="col"><input type="checkbox"></th>
            <th scope="col">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Price</th>
            <th scope="col">Stock</th>
            <th scope="col">Sales</th>
            <th scope="col">Quantity</th>
            <th scope="col">Author</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
        <tr>
            <td><input type="checkbox"> {{$product->id}}</td>
            <td>
                <img src="{{$product->thumbnail()}}" class="w-8 h-8 rounded-full">
            </td>
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
            <td>
                {{$product->authorName()}}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>



    {{ $products->links() }}

</div>


