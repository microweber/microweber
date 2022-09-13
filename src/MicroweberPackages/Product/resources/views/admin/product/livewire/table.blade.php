<div>


    <style>
        #content-results-table tr .mw_admin_posts_sortable_handle {
            visibility: hidden;
        }
        #content-results-table tr:hover .mw_admin_posts_sortable_handle {
            visibility: visible;
        }
    </style>


    <script>


        mw.manage_content_sort = function () {
            if (!mw.$("#content-results-table").hasClass("ui-sortable")) {
                mw.$("#content-results-table").sortable({
                    items: '.manage-post-item',
                    axis: 'y',
                    handle: '.mw_admin_posts_sortable_handle',
                    update: function () {
                        var obj = {ids: []}
                        $(this).find('.js-select-posts-for-action').each(function () {
                            var id = this.attributes['value'].nodeValue;
                            obj.ids.push(id);
                        });
                        $.post("<?php print api_link('content/reorder'); ?>", obj, function () {
                            mw.reload_module('#mw_page_layout_preview');
                            mw.reload_module_parent('posts');
                            mw.reload_module_parent('content');
                            mw.reload_module_parent('shop/products');
                            mw.notification.success('<?php _e("All changes are saved"); ?>.');
                        });
                    },
                    start: function (a, ui) {
                        $(this).height($(this).outerHeight());
                        $(ui.placeholder).height($(ui.item).outerHeight())
                        $(ui.placeholder).width($(ui.item).outerWidth())
                    },
                    scroll: false
                });
            }
        }
    </script>






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
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.visible"> Visibility</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.shop"> Shop</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.date"> Date</label>
                        <label class="dropdown-item"><input type="checkbox" wire:model="showFilters.author"> Author</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-admin-product-filters"  @if (empty($showFilters)) style="display: none"  @endif>
        <div class="container-filters p-3 pt-4 mb-4" style="background: rgb(236, 244, 255)">
            <div class="row">

                @if(isset($showFilters['categoryTags']) && $showFilters['categoryTags'])
                @include('product::admin.product.livewire.table-filters.category')
                @include('product::admin.product.livewire.table-filters.tags')
                @endif

                @if(isset($showFilters['visible']) && $showFilters['visible'])
                @include('product::admin.product.livewire.table-filters.visible')
                @endif

                @if(isset($showFilters['date']) && $showFilters['date'])
                @include('product::admin.product.livewire.table-filters.date')
                @endif
            </div>
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
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Show columns
                </button>
                <div class="dropdown-menu p-3">
                   <label><input type="checkbox" wire:model="showColumns.id"> Id</label>
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
        @foreach ($products as $product)
        <tr class="manage-post-item">
            <td>
                <input type="checkbox" value="{{ $product->id }}"  class="js-select-posts-for-action"  wire:model="checked">


                <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()"><i class="mdi mdi-cursor-move"></i></span>

            </td>
            @if($showColumns['id'])
                <td>
                    {{ $product->id }}
                </td>
            @endif
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
            <td style="text-align: center">
                @php
                $ordersUrl = route('admin.order.index') . '?productId='.$product->id;
                if ($product->salesCount == 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->salesCount.'</span></a>';
                } else if ($product->salesCount > 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->salesCount.'</span></a>';
                } else {
                    $sales = '<span>'.$product->salesCount.'</span>';
                }
                @endphp
                {!! $sales !!}
            </td>
            @endif
            @if($showColumns['quantity'])
            <td style="text-align: center">
            @php
                if ($product->qty == 'nolimit') {
                      $quantity = '<span><i class="fa fa-infinity" title="Unlimited Quantity"></i></span>';
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


