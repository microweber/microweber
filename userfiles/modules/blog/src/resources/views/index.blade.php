{!! $posts->scripts() !!}

<section class="section section-blog">
    <div class="container">
    <div class="row">

            <div class="col-md-3">
                <div class="card">

                    {!! $posts->activeFilters() !!}

                    {!! $posts->search() !!}

                    {!! $posts->tags() !!}

                    {!! $posts->categories() !!}

                    {!! $posts->filters() !!}

                 </div>
            </div>


        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8"> </div>

                <div class="col-md-2">
                    {!! $posts->limit(); !!}
                </div>
                <div class="col-md-2">
                    {!! $posts->sort(); !!}
                </div>
            </div>
            <div class="row">
            @foreach($posts->results() as $post)
                    <div class="col-md-3">
            <div class="post" style="margin-top:25px;">

                <img src="{{$post->thumbnail(400,400)}}" alt="" width="400px">

                <h4>{{$post->title}}</h4>
                <p>{{$post->content_text}}</p>
                <br />
                <small>Posted At:{{$post->posted_at}}</small>
                <br />
                <a href="{{site_url($post->url)}}">View</a>
                <hr />
                @foreach($post->tags as $tag)
                   <span class="badge badge-success"><a href="?tags={{$tag->slug}}">{{$tag->name}}</a></span>
                @endforeach

            </div>
            </div>
            @endforeach
            </div>

            {!! $posts->pagination() !!}

            <br />
            <p>
                Displaying {{$posts->count()}} of {{ $posts->total() }} result(s).
            </p>
        </div>

        </div>
        </div>
</section>
