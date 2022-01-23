{!! $products->scripts() !!}

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-0 text-dark bg-white">

                {!! $products->filtersActive() !!}

                {!! $products->search() !!}

                {!! $products->tags() !!}

                {!! $products->categories() !!}

                {!! $products->filters() !!}

            </div>
        </div>

        <div class="col-lg-9">
            <div class="row">
                <div class="col-xl-6 col-lg-5 col-lg-7 col-lg-2 col-lg-5 py-lg-0 py-4">
                    <p> <?php _e("Displaying"); ?> {{$products->count()}} <?php _e("of"); ?> {{ $products->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-xl-6 col-lg-7 col-lg-5 d-block d-sm-flex justify-content-end ms-auto">
                    <div class="col-12 col-sm px-1 ms-auto">{!! $products->limit(); !!}</div>
                    <div class="col-12 col-sm px-1 ms-auto">{!! $products->sort(); !!}</div>
                </div>
            </div>
            <div class="row">
                @foreach($products->results() as $product)
                    <div class="col-4 mb-5">
                        <a href="{{content_link($product->id)}}">
                            <img src="{{$product->thumbnail(800,800, true)}}" alt="">

                            <h6 class="mt-3">{{$product->title}}</h6>
                        </a>
                        <p>{{$product->content_text}}</p>

                        <div class="d-flex">
                            <p class="col-6 mb-0">
                                @if($product->hasSpecialPrice())
                                    <span class="price-old"><?php print currency_format($product->specialPrice); ?></span>
                                @endif
                                <span class="money"><?php print currency_format($product->price); ?></span>
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
</div>








