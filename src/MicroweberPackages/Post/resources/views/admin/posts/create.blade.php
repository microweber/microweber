@extends('admin::layouts.app')



@section('content')

    @if($post_design)
        @section('topbar2-links-right')
            @include('content::admin.content.topbar-parts.links-right')
        @endsection


    <module type="content/edit" content_id="{{$content_id}}" content_type="post"
            parent="{{$recommended_content_id}}" id="main-content-edit-admin"
            category="{{$recommended_category_id}}"   />
    @else






    <div class="px-5 mx-5 js-choose-post-design">
        <h3 class="main-pages-title ms-1">{{ _e("Choose Post Design") }}</h3>
        <small class="text-muted ms-1">{{ _e("Choose the right design for your article. You can use the predefined suggestions and replace the information with your own content") }}</small>


        <div class="row row-cards px-3 pt-4">

            @foreach($allLayouts as $layout)

                <div class="col-xxl-4 col-lg-6 col-12 pe-lg-6 ps-lg-0 pt-3 mb-4 ">
                    <div class="card mw-create-page-admin-wrapper">
                        <div id="mw-create-page-card-overlay"></div>




                        @include('page::admin.page.iframe', [
                         'iframeWidth'=>'181%',
                         'transformScale'=>'0.553',
                         'url'=>site_url('new-content-preview-'. uniqid() . '?content_id=0&no_editmode=true&preview_layout=' . $layout['layout_file_preview']
                    )])
                        <div class="p-2 text-center mw-create-page-admin-create-btn">
                            <a href="{{route('admin.post.create')}}?layout={{$layout['layout_file_preview']}}" class="btn btn-primary">
                                {{ _e("Create") }}
                            </a>
                        </div>
                    </div>

                    <h2 class="mt-3 mb-2 font-weight-bold">{{$layout['name']}} </h2>
                    <p>{{ _e("Start clean with a blank page and create a page layout of your own design.") }}</p>


                </div>

            @endforeach


        </div>
    </div>
    @endif

@endsection
