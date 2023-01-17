<div>

    <table class="table table-responsive" id="content-results-table">
        <thead>
        <tr>
            <th scope="col">

                @include('content::admin.content.livewire.table-includes.select-all-checkbox')

            </th>
            @if($showColumns['id'])
                @include('product::admin.product.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
            @endif
            @if($showColumns['image'])
                <th style="width: 130px" scope="col">Image</th>
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
        @if (count($contents)==0)
            <tr>
                <td colspan="{{count($showColumns)}}">
                    {{_e('No items found')}}
                </td>
            </tr>
        @endif
        @foreach ($contents as $content)
            <tr class="manage-post-item manage-post-item-{{ $content->id }}">
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                        <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                    </div>

                    <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                        <i class="mdi mdi-cursor-move"></i>
                    </span>
                </td>
                @if($showColumns['id'])
                    <td>
                        {{ $content->id }}
                    </td>
                @endif
                @if($showColumns['image'])
                    <td>
                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])
                    </td>
                @endif
                @if($showColumns['title'])
                    <td>
                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])
                    </td>
                @endif
                @if($showColumns['price'])
                    <td>
                        @php
                            if ($content->hasSpecialPrice()) {
                                $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($content->price).'</span>';
                                $price .= '<br /><span class="h5">'.currency_format($content->specialPrice).'</span>';
                            } else {
                                $price = '<span class="h5">'.currency_format($content->price).'</span>';
                            }
                        @endphp

                        {!! $price !!}
                    </td>
                @endif
                @if($showColumns['stock'])
                    <td>
                        @php
                            if ($content->InStock) {
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
                            $ordersUrl = route('admin.order.index') . '?showFilters[productId]=1&filters[productId]='.$content->id;
                            if ($content->ordersCount == 1) {
                                $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$content->ordersCount.'</span></a>';
                            } else if ($content->ordersCount > 1) {
                                $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$content->ordersCount.'</span></a>';
                            } else {
                                $sales = '<span>'.$content->ordersCount.'</span>';
                            }
                        @endphp
                        {!! $sales !!}
                    </td>
                @endif
                @if($showColumns['quantity'])
                    <td style="text-align: center">
                        @php
                            if ($content->qty == 'nolimit') {
                                  $quantity = '<span><i class="fa fa-infinity" title="Unlimited Quantity"></i></span>';
                              } else if ($content->qty == 0) {
                                  $quantity = '<span class="text-small text-danger">0</span>';
                              } else {
                                  $quantity = $content->qty;
                              }
                        @endphp
                        {!! $quantity !!}
                    </td>
                @endif
                @if($showColumns['author'])
                    <td>
                        {{$content->authorName()}}
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
