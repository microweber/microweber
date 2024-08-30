
    <a class="text-decoration-none" href="{{content_link($product->id)}}">
        <div class="background-image-holder" style="background-image: url('{{$product->thumbnail(1000,1000)}}'); height: 450px; background-size: contain;">

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

        </div>
        <h6 class="mt-3">{{$product->title}}</h6>
    </a>


    <p>{{$product->content_text}}</p>

    <div class="d-flex items-center text-center align-items-center price-holder">

        @if($product->hasSpecialPrice())
            <h6 class="price-old mb-0"><?php print currency_format($product->price); ?></h6>
            <h6 class="price mb-0"><?php print currency_format($product->specialPrice); ?></h6>
        @else
            <h6 class="price mb-0"><?php print currency_format($product->price); ?></h6>
        @endif

    </div>

    @foreach($product->tags as $tag)
        <span class="badge badge-lg"><a href="?tags[]={{$tag->slug}}">{{$tag->name}}</a></span>
    @endforeach

