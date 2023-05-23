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
                <div class="d-flex flex-start mb-4">
                    <div class="shadow-1-strong me-3">
                        <i class="fa fa-user-circle-o" style="font-size:42px"></i>
                    </div>
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <div>
                                <h5>{{$comment->comment_name}}</h5>
                                <p class="text-small">
                                    {{$comment->created_at->diffForHumans()}}
                                </p>
                                <p class="mt-3 mb-3">
                                    {{$comment->comment_body}}
                                </p>

                                <div class="d-flex justify-content-end align-items-center mt-4">
                                    <button type="button" class="btn btn-outline-primary"><i class="fa fa-reply me-1"></i> Reply</button>
                                </div>
                            </div>

                            <div style="background:#fff;border-radius:3px;" class="mt-2 p-4">
                                @include('comments::comment_reply', [
                                    'parent_id' => $comment->id,
                                ])
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
