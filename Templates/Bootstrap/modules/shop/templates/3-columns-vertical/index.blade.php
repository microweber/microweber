<?php

/*

type: layout

name: 3 columns vertical

description: 3 columns vertical

*/

?>
{!! $products->scripts() !!}
<script>
    $(document).ready(function(){

        $(".shopmag-shop-filter").click(function(){
            $(".shopmag-shop-column").toggleClass("has-filter");
        });
    });
    function productQuickView(product_id, modal_title = false) {
        $.get(mw.settings.api_url + 'product/quick-view', {id:product_id},
            function (data) {
                // $('.js-shopmang2-modal').find('.modal-title').html(modal_title);
                $('.js-shopmang2-modal').find('.modal-body').html(data);
                $('.js-shopmang2-modal').modal('show');
            }
        );
    }
</script>
<div class="container-fluid">
    <div class="row shop-products pt-7">

        @if($products->hasFilter())
            <div class="shopmag-shop-column shopmag-shop-left-column col-lg-3">
                <nav class=" navbar-expand-lg navbar-light">
                    <button class="navbar-toggler btn-outline-primary w-100" type="button" data-toggle="collapse" data-target="#shopmag-shop-filters-hamburger" aria-controls="shopmag-shop-filters-hamburger" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="mdi mdi-filter-outline"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="shopmag-shop-filters-hamburger">
                        <div id="accordion">
                            <div class="card border-0 text-dark bg-white">

                                {!! $products->filtersActive() !!}

                                {!! $products->search() !!}

                                {!! $products->categories() !!}

                                {!! $products->tags() !!}

                                {!! $products->filters() !!}

                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        @endif

        <div class="shopmag-shop-column shopmag-shop-right-column @if($products->hasFilter()) col-lg @else col-lg-12 @endif">
            <div class="row">
                <div class="col-xl-6 col-lg-5 col-lg-7 col-lg-2 col-lg-5 py-lg-0 py-4">
                    <p> <?php _e("Displaying"); ?> {{$products->count()}} <?php _e("of"); ?> {{ $products->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-xl-6 col-lg-7 col-lg-5 d-block d-sm-flex justify-content-end">
                    <div class="col-12 col-sm px-1 ms-auto">{!! $products->limit(); !!}</div>
                    <div class="col-12 col-sm px-1 ms-auto">{!! $products->sort(); !!}</div>
                    @if($products->hasFilter())
                        <a class="shopmag-shop-filter d-lg-block d-none col-12 col-sm-1 btn btn-outline-primary align-self-center px-2 mb-3 ms-auto"><i class="theme-icon-slider mdi mdi-filter-outline"></i></a>
                    @endif
                </div>
            </div>

            <div class="row">
                @foreach($products->results() as $product)
                    <div class="col-md-4 item-{{$product->id}} mb-5">

                        <div class="product">
                            <input type="hidden" name="content_id" value="{{$product->id}}"/>

                            <a href="{{content_link($product->id)}}" class="d-flex h-100 w-100">
                                <div class="image" style="background-image: url({{$product->thumbnail(1800,2400, true)}} ">
                                    {{--<img src="{{$product->thumbnail(1200,1600, true)}}" alt="">--}}

                                    <div class="hover">
                                        <?php if ($product->inStock == true): ?>
                                        <a href="javascript:;" onclick="mw.cart.add('.shop-products .item-{{$product->id}}', '{{$product->price}}','{{$product->title}}');" class="btn btn-default"><i class="mw-micon-Shopping-Cart"></i></a>
                                        <a href="{{content_link($product->id)}}" class="btn btn-default"><i class="mw-micon-Eye-2"></i></a>
                                        <?php else: ?>
                                        <a href="javascript:;" onclick="alert('This product is out of stock');" class=" btn btn-default"><i class="mw-micon-Close"></i></a>
                                        <a href="{{content_link($product->id)}}" class="btn btn-default"><i class="mw-micon-Eye-2"></i></a>
                                        <?php endif; ?>

                                        <a href="#" onclick="productQuickView('{{$product->id}}', '{{_e('Quick view ')}} {{$product->title}}')" class="btn btn-default"><i class="mw-micon-Search-People"></i></a>
                                    </div>
                                </div>
                            </a>

                            <div class="mt-3">
                                <a href="{{content_link($product->id)}}">
                                    <div class="title pb-2">{{$product->title}}</div>
                                </a>


                                <div class="mb-2">
                                    @foreach($product->tags as $tag)
                                        <span class="badge badge-lg p-0"><a href="?tags[]={{$tag->slug}}">{{$tag->name}}</a></span>
                                    @endforeach
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-md-12 price-holder">
                                        <p>
                                @if($product->hasSpecialPrice())
                                    <span class="price-old"><?php print currency_format($product->price); ?></span>
                                    <span class="money"><?php print currency_format($product->specialPrice); ?></span>
                                @else
                                    <span class="money"><?php print currency_format($product->price); ?></span>
                                @endif
                            </p>
                                    </div>

                                    {{--<div class="d-md-none col-6 d-flex justify-content-end">--}}
                                    {{--<?php if ($product->inStock == true): ?>--}}
                                    {{--<a href="javascript:;" onclick="mw.cart.add_item('{{$product->id}}','{{$product->price}}', '{{$product->title}}')" class="btn btn-outline-primary"><i class="mw-micon-Shopping-Cart"></i></a>--}}
                                    {{--<?php else: ?>--}}
                                    {{--<span class="text-danger p-1"><i class="material-icons" style="font-size: 18px;">remove_shopping_cart</i> <?php _lang("Out of Stock", 'templates/shopmag') ?></span>--}}
                                    {{--<?php endif; ?>--}}
                                    {{--</div>--}}
                                </div>
                            </div>


                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row mb-5">
            {!! $products->pagination('pagination::microweber') !!}
        </div>
    </div>
</div>





