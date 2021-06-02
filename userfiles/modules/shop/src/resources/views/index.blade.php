{!! $products->scripts() !!}

<section class="section section-blog">
    <div class="container">
    <div class="row">

        <div class="col-md-3">
            <div class="card">

                {!! $products->activeFilters() !!}

                {!! $products->search() !!}

                {!! $products->tags() !!}

                {!! $products->categories() !!}

                {!! $products->filters() !!}

             </div>
        </div>


        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    {!! $products->limit(); !!}
                </div>
                <div class="col-md-2">
                    {!! $products->sort(); !!}
                </div>
            </div>
            <div class="row">
            @foreach($products->results() as $product)
                    <div class="col-md-3">
            <div class="post" style="margin-top:25px;">

                <img src="{{$product->thumbnail(400,400)}}" alt="" width="400px">

                <h4>{{$product->title}}</h4>
                <p>{{$product->content_text}}</p>
                <br />
                <small>Posted At:{{$product->posted_at}}</small>
                <br />
                <a href="{{site_url($product->url)}}">View</a>
                <hr />
                @foreach($product->tags as $tag)
                   <span class="badge badge-success"><a href="?tags={{$tag->slug}}">{{$tag->name}}</a></span>
                @endforeach

                @php
                    $resultCustomFields = $product->customField()->with('fieldValue')->get();
                @endphp
                @foreach ($resultCustomFields as $resultCustomField)
                    {{--@if ($resultCustomField->type !== 'date')
                        @continue
                    @endif--}}
                    {{$resultCustomField->name}}:
                    @php
                        $customFieldValues = $resultCustomField->fieldValue()->get();
                    @endphp
                    @foreach($customFieldValues as $customFieldValue)
                        {{$customFieldValue->value}};
                    @endforeach

                @endforeach
            </div>
            </div>
            @endforeach
            </div>

            {!! $products->pagination() !!}

            <br />
            <p>
                Displaying {{$products->count()}} of {{ $products->total() }} result(s).
            </p>
        </div>


        </div>
        </div>
</section>
