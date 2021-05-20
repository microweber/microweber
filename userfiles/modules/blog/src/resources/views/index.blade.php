<section class="section section-blog edit safe-mode nodrop">
    <div class="container">
    <div class="row">

        <div class="col-md-4">


            {!! $posts->limit(); !!}
            {!! $posts->sort(); !!}

        </div>

        <div class="col-md-8">
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

            <p>
                Displaying {{$posts->count()}} of {{ $posts->total() }} product(s).
            </p>
        </div>
        <div class="col-md-12">
            {!! $posts->pagination() !!}
        </div>

    </div>
    </div>
</section>
