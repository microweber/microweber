<section style="background-color: #eee;">

    <div class="container my-5 py-5 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-11 col-lg-9 col-xl-7">
                @include('comments::comment_reply')
            </div>
        </div>
    </div>

    <div class="container py-2 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-11 col-lg-9 col-xl-7">

                @foreach($comments as $comment)
                    @include('comments::comment_preview', [
                               'comment' => $comment,
                           ])
                @endforeach

            </div>
        </div>
    </div>
</section>
