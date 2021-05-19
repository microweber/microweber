<section class="section section-blog edit safe-mode nodrop">
    <div class="container">


        @foreach($posts as $post)
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

        {!! $posts->links('pagination::bootstrap-4-flex') !!}

    </div>
</section>
