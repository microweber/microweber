@extends('admin::layouts.app')

@section('content')

    @if($post_design)
    <module type="content/edit" content_id="{{$content_id}}" content_type="post"
            parent="{{$recommended_content_id}}" id="main-content-edit-admin"
            category="{{$recommended_category_id}}"   />
    @else
    <div class="px-5 mx-5">
        <h3 class="main-pages-title">{{ _e("Choose Post Design") }}</h3>

        <div class="row row-cards px-3">

            <div class="col-xxl-4 col-lg-6 col-12 pe-lg-6 ps-lg-0 pt-3 mb-4">
                <a href="{{route('admin.post.create')}}?post_design=title_with_text" class="card card-link card-link-pop " style="height: 450px; overflow: hidden; ">
                    <div class="card-body" style="padding: 40px;">
                       <h3 style="margin-bottom: 40px;">Awesome Title Is Here</h3>
                        <p style="line-height: 24px;">
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using
                        </p>
                    </div>
                </a>

                <h2 class="mt-3" style="font-size: 18px;">Title with Text</h2>

            </div>

            <div class="col-xxl-4 col-lg-6 col-12 pe-lg-6 ps-lg-0 pt-3 mb-4">
                <a href="{{route('admin.post.create')}}?post_design=image_body_and_text" class="card card-link card-link-pop " style="height: 450px; overflow: hidden; ">
                    <div class="card-body" style="padding: 40px;">
                        <h3 style="margin-bottom: 40px;">Awesome Title Is Here</h3>
                        <div class="text-center py-6 bg-muted-lt" style="margin-bottom: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48"><path d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h600q24 0 42 18t18 42v600q0 24-18 42t-42 18H180Zm0-60h600v-600H180v600Zm56-97h489L578-473 446-302l-93-127-117 152Zm-56 97v-600 600Z"/></svg>
                        </div>
                        <p style="line-height: 24px;">
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here'.
                        </p>
                    </div>

                    <h2 class="mt-3" style="font-size: 18px;">Image Body and Text</h2>

                </a>
            </div>

            <div class="col-xxl-4 col-lg-6 col-12 pe-lg-6 ps-lg-0 pt-3 mb-4">
                <a href="{{route('admin.post.create')}}?post_design=video_with_text" class="card card-link card-link-pop " style="height: 450px; overflow: hidden; ">
                    <div class="card-body" style="padding: 40px;">
                        <h3 style="margin-bottom: 40px;">Awesome Title Is Here</h3>

                        <div class="text-center py-6 bg-muted-lt" style="margin-bottom: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48"><path d="m392-313 260-169-260-169v338ZM140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm0-60h680v-520H140v520Zm0 0v-520 520Z"/></svg>
                        </div>
                        <p style="line-height: 24px;">
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here'.
                        </p>
                    </div>

                    <h2 class="mt-3" style="font-size: 18px;">Video with Text</h2>

                </a>
            </div>

        </div>
    </div>
    @endif

@endsection
