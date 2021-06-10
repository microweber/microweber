{!! $products->scripts() !!}

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 text-dark bg-white">

                {!! $products->filtersActive() !!}

                {!! $products->search() !!}

                {!! $products->tags() !!}

                {!! $products->categories() !!}

                {!! $products->filters() !!}

            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-8">
                    <p> <?php _e("Displaying"); ?> {{$products->count()}} <?php _e("of"); ?> {{ $products->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div class="px-1">{!! $products->limit(); !!}</div>
                    <div class="px-1">{!! $products->sort(); !!}</div>
                </div>
            </div>
            <div class="row">
                @foreach($products->results() as $product)
                    <div class="col-4 mb-5">
                        <a href="{{site_url($product->url)}}">
                            <img src="{{$product->thumbnail(800,800, true)}}" alt="">

                            <h4 class="mt-3">{{$product->title}}</h4>
                        </a>
                        <p>{{$product->content_text}}</p>

                        <hr />

                        @foreach($product->tags as $tag)
                            <span class="badge badge-lg"><a href="?tags={{$tag->slug}}">{{$tag->name}}</a></span>
                        @endforeach
                    </div>
                @endforeach
            </div>
            {!! $products->pagination() !!}
        </div>
    </div>
</div>




