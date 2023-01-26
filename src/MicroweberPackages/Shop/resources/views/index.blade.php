{!! $products->scripts() !!}

@php
$filtersSort = json_decode(get_option('filters-sort', $moduleId), true);
@endphp

<section class="section container-fluid">
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-0 text-dark bg-white">

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

        <div class="col-lg-9">
            <div class="row">
                <div class="col-xl-6 col-lg-5 col-lg-7 col-lg-2 col-lg-5 py-lg-0 py-4">
                    <p> <?php _e("Displaying"); ?> {{$products->count()}} <?php _e("of"); ?> {{ $products->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-xl-6 col-lg-7 col-lg-5 d-block d-sm-flex justify-content-end ms-auto">
                    <div class="col-md-6 col-sm px-1 ms-auto">{!! $products->limit(); !!}</div>
                    <div class="col-md-6 col-sm px-1 ms-auto">{!! $products->sort(); !!}</div>
                </div>
            </div>
            <div class="row">
                @foreach($products->results() as $product)

                    <?php
                        /* @var $product \MicroweberPackages\Product\Models\Product */

                        ?>
                    <div class="col-xl-4 col-lg-6 col-sm-12 mb-5">
                        <a href="{{content_link($product->id)}}">
                            <div class="img-as-background square-75 h-350 position-relative">

                                <div @if($product->getLabelColor())
                                         style="background-color: {{$product->getLabelColor()}} "
                                    @endif >
                                    @if($product->getLabelType() == 'percent')
                                        <div class="discount-label">
                                                <span class="discount-percentage">
                                                      {{$product->getDiscountPercentage()}} %
                                                </span>
                                            <span class="discount-label-text"><?php _lang("Discount"); ?></span>
                                        </div>

                                    @endif
                                    @if($product->getLabelType() == 'text')
                                        <p>{{$product->getLabelText()}}  </p>
                                    @endif
                                </div>

                                <img src="{{$product->thumbnail(1000,1000)}}" />

                            </div>
                            <h6 class="mt-3">{{$product->title}}</h6>
                        </a>


                        <p>{{$product->content_text}}</p>




                        <div class="d-flex">
                            <p class="col-6 mb-0">
                                @if($product->hasSpecialPrice())
                                    <span class="price-old"><?php print currency_format($product->price); ?></span>
                                    <span class="money"><?php print currency_format($product->specialPrice); ?></span>
                                @else
                                    <span class="money"><?php print currency_format($product->price); ?></span>
                                @endif
                            </p>

                            <a class="col-6 text-end text-right align-self-center" href="{{content_link($product->id)}}"> View</a>
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








