{!! $posts->scripts() !!}

<div class="container-fluid">
    <div class="row pt-5">
        <div class="col-lg-3">
            <div class="card border-0 text-dark bg-white">

                {!! $posts->filtersActive() !!}

                {!! $posts->search() !!}

                {!! $posts->categories() !!}

                {!! $posts->tags() !!}

                {!! $posts->filters() !!}

             </div>
        </div>

        <div class="col-lg-9">
            <div class="row">
                <div class="col-xl-6 col-lg-5 col-lg-7 col-lg-2 col-lg-5 py-lg-0 py-4">
                    <p> <?php _e("Displaying"); ?> {{$posts->count()}} <?php _e("of"); ?> {{ $posts->total() }}  <?php _e("result(s)"); ?>.</p>
                </div>
                <div class="col-xl-6 col-lg-7 col-lg-5 d-block d-sm-flex justify-content-end ms-auto">
                    <div class="col-md-6 col-12 col-sm px-1 ms-auto">{!! $posts->limit(); !!}</div>
                    <div class="col-md-6 col-12 col-sm px-1 ms-auto">{!! $posts->sort(); !!}</div>
                </div>
            </div>
            <div class="row">
            @foreach($posts->results() as $post)
                <div class="col-md-6 mb-5">
                    <a href="{{content_link($post->id)}}">

                        <?php
                        /*<img src="{{$post->thumbnail(800,500, true)}}" alt="">*/
                        ?>

                        <img src="{{app()->content_repository->getThumbnail($post->id,800,500, true)}}" alt="">

                        <h4 class="mt-3">{{$post->title}}</h4>
                    </a>
                    <p> {!! $post->shortDescription(220) !!}</p>

                    <small>{{$post->created_at}}</small>
                    {{--<hr />--}}

                    {{--@foreach($post->tags as $tag)--}}
                       {{--<span class="badge badge-lg"><a href="?tags[]={{$tag->slug}}">{{$tag->name}}</a></span>--}}
                    {{--@endforeach--}}
                </div>
            @endforeach
            </div>
            {!! $posts->pagination() !!}
        </div>
    </div>
</div>




