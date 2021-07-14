{!! $posts->scripts() !!}

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 text-dark bg-white">

                {!! $posts->filtersActive() !!}

                {!! $posts->search() !!}

                {!! $posts->tags() !!}

                {!! $posts->categories() !!}

                {!! $posts->filters() !!}

             </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-8">
                    <p> <?php _e("Displaying"); ?> {{$posts->count()}} <?php _e("of"); ?> {{ $posts->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div class="px-1">{!! $posts->limit(); !!}</div>
                    <div class="px-1">{!! $posts->sort(); !!}</div>
                </div>
            </div>
            <div class="row">
            @foreach($posts->results() as $post)
                <div class="col-4 mb-5">
                    <a href="{{site_url($post->url)}}">
                        <img src="{{$post->thumbnail(800,500, true)}}" alt="">

                        <h4 class="mt-3">{{$post->title}}</h4>
                    </a>
                    <p>{{$post->content_text}}</p>
                    {{--<small>Posted At:{{$post->created_at}}</small>--}}
                    <hr />

                    @foreach($post->tags as $tag)
                       <span class="badge badge-lg"><a href="?tags[]={{$tag->slug}}">{{$tag->name}}</a></span>
                    @endforeach
                </div>
            @endforeach
            </div>
            {!! $posts->pagination() !!}
        </div>
    </div>
</div>




