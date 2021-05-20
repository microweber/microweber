<section class="section section-blog">
    <div class="container">
    <div class="row">

        <div class="col-md-3">
            {!! $posts->tags('blog::partials.tags'); !!}

            @if (!empty($_GET))
                <a href="{{ URL::current() }}" class="btn btn-outline-primary btn-sm"><i class="fa fa-times"></i> <?php _e('Reset filter'); ?></a>
            @endif
        </div>

        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8">
                    {!! $posts->search('blog::partials.search'); !!}
                </div>
                <div class="col-md-2 ">
                    {!! $posts->limit('blog::partials.limit'); !!}
                </div>
                <div class="col-md-2">
                    {!! $posts->sort('blog::partials.sort'); !!}
                </div>
            </div>

            @foreach($posts->results() as $post)

            <div class="post">
                <h3>{{$post->title}}</h3>
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
            @endforeach

            {!! $posts->pagination('pagination::bootstrap-4-flex') !!}

            <br />
            <p>
                Displaying {{$posts->count()}} of {{ $posts->total() }} result(s).
            </p>
        </div>

    </div>
    </div>
</section>
