<section class="section section-blog edit safe-mode nodrop">
    <div class="container">


        @foreach($posts as $post):
        <div class="post">
            <h3>{{$post['title']}}</h3>
            <p></p>
            <small>Posted At:{{$post['posted_at']}}</small>
            <a href="{{site_url($post['url'])}}">View</a>
            <hr />

        </div>
        @endforeach

    </div>
</section>
