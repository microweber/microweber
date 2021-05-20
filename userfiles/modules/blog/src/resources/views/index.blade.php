<section class="section section-blog edit safe-mode nodrop">
    <div class="container">
    <div class="row">

        <div class="col-md-3">
            <div class="row">

            </div>
        </div>

        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8">
                    {!! $posts->search('blog::search'); !!}
                </div>
                <div class="col-md-2 ">
                    {!! $posts->limit('blog::limit'); !!}
                </div>
                <div class="col-md-2">
                    {!! $posts->sort('blog::sort'); !!}
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
