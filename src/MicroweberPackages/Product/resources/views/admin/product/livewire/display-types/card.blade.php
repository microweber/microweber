<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center flex-lg-box">

                    <div class="col-1 text-center" style="max-width: 40px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>
                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                            <i class="mdi mdi-cursor-move"></i>
                        </span>
                    </div>

                    @if($showColumns['image'])
                    <div class="col-2" style="min-width: 120px; max-width: 120px;">

                        <div class="mw-admin-product-item-icon text-muted">
                            <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title=""></i>
                        </div>

                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])

                    </div>
                    @endif

                    @if($showColumns['title'])
                    <div class="col-xl-5 col-7">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])

                    </div>
                    @endif

                    <div class="col-xl-4 col-sm-12 col-6 d-flex flex-wrap my-xl-0 my-3">
                    @if($showColumns['price'])
                    <div class="col">
                        @php
                            if ($content->hasSpecialPrice()) {
                                $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($content->price).'</span>';
                                $price .= '<br /><span>'.currency_format($content->specialPrice).'</span>';
                            } else {
                                $price = '<span class="h5">'.currency_format($content->price).'</span>';
                            }
                        @endphp

                        {!! $price !!}
                    </div>
                    @endif

                    @if($showColumns['stock'])
                    <div class="col">
                        @php
                            if ($content->InStock) {
                              $stock = '<span class="badge badge-success badge-sm">In stock</span>';
                          } else {
                              $stock = '<span class="badge badge-danger badge-sm">Out Of Stock</span>';
                          }
                        @endphp
                        {!! $stock !!}
                    </div>
                    @endif

                    @if($showColumns['orders'])
                    <div class="col">
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
                       Sales: {!! $sales !!}
                    </div>
                    @endif

                    @if($showColumns['quantity'])
                    <div class="col">
                        @php
                            if ($content->qty == 'nolimit') {
                                  $quantity = '<span><i class="fa fa-infinity" title="Unlimited Quantity"></i></span>';
                              } else if ($content->qty == 0) {
                                  $quantity = '<span class="text-small text-danger">0</span>';
                              } else {
                                  $quantity = $content->qty;
                              }
                        @endphp
                        Qty: {!! $quantity !!}
                    </div>
                    @endif

                    </div>

                    @if($showColumns['author'])
                    <div class="col">
                        <span class="text-muted" title="{{$content->authorName()}}">{{$content->authorName()}}</span>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    @endforeach
</div>
