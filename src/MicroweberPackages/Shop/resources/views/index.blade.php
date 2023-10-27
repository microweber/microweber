{!! $products->scripts() !!}

@php
$filtersSort = json_decode(get_option('filters-sort', $moduleId), true);
@endphp

<style>

    .mw-shop-module-wrapper .card .card-header:after {
        border: none!important;
    }

</style>

<section class="section mw-shop-module-wrapper shop-products">
    <div class="row product">
        <div class="col-xl-3 mb-xl-0 mb-3">
            <div class="card border-0">

                {!! $products->filtersActive() !!}

                @if(!empty($filtersSort))
                    @foreach($filtersSort as $filterSort)

                        @if($filterSort == 'search')
                            {!! $products->search() !!}
                        @endif

                        @if($filterSort == 'categories')
                            {!! $products->categories() !!}
                        @endif

                        @if($filterSort == 'tags')
                            {!! $products->tags() !!}
                        @endif

                        @if($filterSort == 'filters')
                            {!! $products->filters() !!}
                        @endif
                    @endforeach
                @else
                    {!! $products->search() !!}
                    {!! $products->categories() !!}
                    {!! $products->tags() !!}
                    {!! $products->filters() !!}
                @endif

            </div>
        </div>

        <div class="col-xl-9">
            <div class="row">
                <div class="col-xl-6 col-lg-5 col-lg-7 col-lg-2 col-lg-5 py-lg-0 py-4">
                    <p> <?php _e("Displaying"); ?> {{$products->count()}} <?php _e("of"); ?> {{ $products->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-xl-6 col-lg-7 d-block d-sm-flex justify-content-end ms-auto">
                    <div class="col-lg-3 col-md-6 col-sm px-1 ">{!! $products->limit(); !!}</div>
                    <div class="col-lg-3 col-md-6 col-sm px-1 ">{!! $products->sort(); !!}</div>
                </div>
            </div>
            <div class="row">
                @foreach($products->results() as $product)

                    <?php
                        /* @var $product \MicroweberPackages\Product\Models\Product */

                        ?>
                    <div class="col-xl-4 col-lg-6 col-sm-12 mb-5">
                        <a class="text-decoration-none" href="{{content_link($product->id)}}">
                            <div class="img-as-background square-75 h-350 position-relative">

                                <div @if($product->getContentDataByFieldName('label-color'))
                                         style="background-color: {{$product->getContentDataByFieldName('label-color')}} "
                                    @endif >
                                    @if($product->getContentDataByFieldName('label-type') == 'percent')
                                        <div class="discount-label">
                                                <span class="discount-percentage">
                                                      {{$product->getDiscountPercentage()}} %
                                                </span>
                                            <span class="discount-label-text"><?php _lang("Discount"); ?></span>
                                        </div>

                                    @endif
                                    @if($product->getContentDataByFieldName('label-type') == 'text' and $product->getContentDataByFieldName('label'))

                                            <div class="position-absolute  top-0 left-0 m-2" style="z-index: 3;">
                                                <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: {{$product->getContentDataByFieldName('label-color')}};">{{$product->getContentDataByFieldName('label')}}</div>
                                            </div>
                                    @endif
                                </div>

                                <img src="{{$product->thumbnail(1000,1000)}}" />

                            </div>
                            <h6 class="mt-3">{{$product->title}}</h6>
                        </a>


                        <p>{{$product->content_text}}</p>




                        <div class="d-flex align-items-center price-holder">

                                @if($product->hasSpecialPrice())
                                    <h6 class="price-old mb-0"><?php print currency_format($product->price); ?></h6>
                                    <h6 class="price mb-0"><?php print currency_format($product->specialPrice); ?></h6>
                                @else
                                    <h6 class="price mb-0"><?php print currency_format($product->price); ?></h6>
                                @endif


                        </div>

                        {{--@foreach($product->tags as $tag)--}}
                            {{--<span class="badge badge-lg"><a href="?tags[]={{$tag->slug}}">{{$tag->name}}</a></span>--}}
                        {{--@endforeach--}}
                    </div>
                @endforeach
            </div>
            {!! $products->pagination() !!}
        </div>
    </div>
</section>








