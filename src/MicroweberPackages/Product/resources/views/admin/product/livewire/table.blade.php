<div>

    {!! json_encode($filters, JSON_PRETTY_PRINT) !!}

    <button class="btn btn-outline-danger" wire:click="clearFilters">Clear filers</button>

    <div id="js-admin-product-filters">

        <div style="background: rgb(236, 244, 255) none repeat scroll 0% 0%;" class="p-3 pt-4 mb-4">
            <div class="container-filters">
                <div class="row">

                    <div class=" col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                        <input type="hidden" id="js-price-range" wire:model.stop="filters.priceBetween">
                        <label class="d-block">
                            Price range
                        </label>
                        <div class="mb-3 mb-md-0 input-group">
                            <span class="input-group-text">From</span>
                            <input type="number" class="form-control" id="js-price-min" placeholder="Min price">
                            <span class="input-group-text">To</span>
                            <input type="number" class="form-control" id="js-price-max" placeholder="Max Price">
                        </div>
                        <script>
                            document.addEventListener('livewire:load', function () {

                                let priceMin = document.getElementById('js-price-min');
                                let priceMax = document.getElementById('js-price-max');
                                let priceRangeValue = priceMin.value + ', ' + priceMax.value;
                                let priceRangeElement = document.getElementById('js-price-range');

                                const priceRangeExp = priceRangeElement.value.split(",");
                                if (priceRangeExp) {
                                    if (priceRangeExp[0]) {
                                        priceMin.value = priceRangeExp[0];
                                    }
                                    if (priceRangeExp[1]) {
                                        priceMax.value = priceRangeExp[1];
                                    }
                                }

                                priceMin.onkeyup = function() {
                                    priceRangeValue = priceMin.value + ',' + priceMax.value;
                                    priceRangeElement.value = priceRangeValue;
                                    priceRangeElement.dispatchEvent(new Event('input'));
                                };

                                priceMax.onkeyup = function() {
                                    priceRangeValue = priceMin.value + ',' + priceMax.value;
                                    priceRangeElement.value = priceRangeValue;
                                    priceRangeElement.dispatchEvent(new Event('input'));
                                };
                            });
                        </script>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                        <label class="d-block">
                            Stock Status
                        </label>

                        <select wire:model.stop="filters.inStock" class="form-control">
                            <option value="">Any</option>
                            <option value="1">In Stock</option>
                            <option value="0">Out Of Stock</option>
                        </select>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                        <label class="d-block">
                            Discount
                        </label>

                        <select wire:model.stop="filters.discounted" class="form-control">
                            <option value="">Any</option>
                            <option value="1">Discounted</option>
                            <option value="0">Not discounted</option>
                        </select>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
                        <label class="d-block">
                            Sales
                        </label>

                        <div class="mb-3 mb-md-0 input-group">
                            <select class="form-control" wire:model.stop="filters.salesOperator">
                                <option value="">Equal</option>
                                <option value="greater">More than</option>
                                <option value="lower">Lower than</option>
                            </select>
                            <input type="number" class="form-control" placeholder="Sales count" wire:model.stop="filters.sales">
                        </div>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-1 mb-4 ">
                        <label class="d-block">
                            Quantity
                        </label>

                        <div class="mb-3 mb-md-0 input-group">
                            <input wire:model.stop="filters.qty" type="number" class="form-control">
                        </div>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
                        <label class="d-block">
                            SKU
                        </label>

                        <div class="mb-3 mb-md-0 input-group">
                            <input wire:model.stop="filters.contentData.sku" type="text" class="form-control">
                        </div>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
                        <label class="d-block">
                            Visible
                        </label>

                        <select wire:model.stop="filters.visible" class="form-control">
                            <option value="">Any</option>
                            <option value="1">Published</option>
                            <option value="0">Unpublished</option>
                        </select>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
                        <label class="d-block">
                            Created at
                        </label>

                        <div class="mb-3 mb-md-0 input-group">
                            <input wire:model.stop="filters.created_at" type="date" class="form-control">
                        </div>

                    </div>

                    <div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
                        <label class="d-block">
                            Updated at
                        </label>
                        <div class="mb-3 mb-md-0 input-group">
                            <input wire:model.stop="filters.updated_at" type="date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
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
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
        <tr>
            <td><input type="checkbox"></td>
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
        </tr>
        @endforeach
        </tbody>
    </table>



    {{ $products->links() }}

</div>


