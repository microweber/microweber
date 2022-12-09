<div>

    <table class="table table-responsive" id="content-results-table">
        <thead>
        <tr>
            <th scope="col">

                <div class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" wire:model="selectAll" id="select-all-products" class="custom-control-input">
                    <label for="select-all-products" class="custom-control-label"></label>
                </div>

            </th>
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
            @if($showColumns['orders'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'Orders', 'key'=>'orders', 'filters'=>$filters])
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
        @if (count($products)==0)
            <tr>
                <td colspan="{{count($showColumns)}}">
                    {{_e('No items found')}}
                </td>
            </tr>
        @endif
        @foreach ($products as $product)
            <tr class="manage-post-item manage-post-item-{{ $product->id }}">
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="{{ $product->id }}" id="products-{{ $product->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                        <label for="products-{{ $product->id }}" class="custom-control-label"></label>
                    </div>

                    <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                    <i class="mdi mdi-cursor-move"></i>
                </span>
                </td>
                @if($showColumns['id'])
                    <td>
                        {{ $product->id }}
                    </td>
                @endif
                @if($showColumns['image'])
                    <td style="width:160px;">
                        @if($product->media()->first())
                            <img src="{{$product->thumbnail(200,200)}}" class="rounded-full">
                        @else
                            <div class="img-circle-holder border-radius-0 border-0">
                                <i class="mdi mdi-shopping mdi-48px text-muted text-opacity-5"></i>
                            </div>
                        @endif
                    </td>
                @endif
                @if($showColumns['title'])
                    <td>

                        <div class="manage-item-main-top">

                            <a target="_self" href="{{route('admin.product.edit', $product->id)}}" class="btn btn-link p-0">
                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
                                    {{$product->title}}
                                </h5>
                            </a>
                            @if($product->categories->count() > 0)
                                <span class="manage-post-item-cats-inline-list">
                                @foreach($product->categories as $category)
                                    @if($category->parent)

                                    <a onclick="livewire.emit('selectCategoryFromTableList', {{$category->parent->id}});return false;" href="?filters[category]={{$category->parent->id}}&showFilters[category]=1"
                                       class="btn btn-link p-0 text-muted">
                                        {{$category->parent->title}}
                                    </a>

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
                            <?php if(!$product->is_deleted): ?>
                            <a href="javascript:mw.admin.content.delete('{{ $product->id }}');" class="btn btn-outline-danger btn-sm js-delete-content-btn-{{ $product->id }}">Delete</a>
                            <?php endif; ?>
                            @if ($product->is_active < 1)
                                <a href="javascript:mw.admin.content.publishContent('{{ $product->id }}');" class="mw-set-content-unpublish badge badge-warning font-weight-normal">Unpublished</a>

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
                @if($showColumns['orders'])
                    <td style="text-align: center">
                        @php
                            $ordersUrl = route('admin.order.index') . '?showFilters[productId]=1&filters[productId]='.$product->id;
                            if ($product->ordersCount == 1) {
                                $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->ordersCount.'</span></a>';
                            } else if ($product->ordersCount > 1) {
                                $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->ordersCount.'</span></a>';
                            } else {
                                $sales = '<span>'.$product->ordersCount.'</span>';
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

</div>
