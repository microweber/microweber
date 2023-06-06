<div id="content-results-table">
    @foreach ($contents as $content)

        <div class="card card-product-holder mb-2 post-has-image-true manage-post-item mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center flex-lg-box">

                    <div class="col-1 text-center" style="max-width: 40px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="{{ $content->id }}" id="products-{{ $content->id }}"  class="js-select-posts-for-action form-check-input"  wire:model="checked">
                            <label for="products-{{ $content->id }}" class="custom-control-label"></label>
                        </div>
                        <span class="cursor-move-holder me-2 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                              <span href="javascript:;" class="btn btn-link text-blue-lt">
                                  <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                              </span>
                        </span>
                    </div>

                    @if($showColumns['image'])
                    <div class="col-auto mx-4" style="min-width: 120px; max-width: 120px;">
                        @include('content::admin.content.livewire.components.picture', ['content'=>$content])
                    </div>
                    @endif

                    @if($showColumns['title'])
                    <div class="col-md col-12 my-md-0 my-3">

                        @include('content::admin.content.livewire.components.title-and-categories', ['content'=>$content])

                    </div>
                    @endif

                    <div class="col-auto d-flex flex-wrap my-xl-0 my-3">

                        @if($showColumns['orders'])
                        <div class="mx-1">
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
                        <div class="mx-1">
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

                    <div class="col-1 text-end item-author manage-post-item-col-4">
                        @include('content::admin.content.livewire.components.manage-links', ['content'=>$content])
                    </div>

                </div>
            </div>
        </div>

    @endforeach
</div>
