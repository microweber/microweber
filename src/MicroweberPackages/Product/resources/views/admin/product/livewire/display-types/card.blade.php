<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item">
            <div class="card-body">
                <div class="row align-items-center flex-lg-box">

                    <div class="col text-center manage-post-item-col-1" style="max-width: 40px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action custom-control-input"  wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>
                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()">
                            <i class="mdi mdi-cursor-move"></i>
                        </span>
                    </div>

                    <div class="col manage-post-item-col-2" style="max-width: 120px;">

                        <div class="mw-admin-product-item-icon text-muted">
                            <i class="mdi mdi-shopping mdi-18px" data-bs-toggle="tooltip" title=""></i>
                        </div>

                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])


                    </div>

                    <div class="col item-title manage-post-item-col-3 manage-post-main">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])

                    </div>

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

                    <div class="col">
                        <span class="text-muted" title="{{$content->authorName()}}">{{$content->authorName()}}</span>
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
