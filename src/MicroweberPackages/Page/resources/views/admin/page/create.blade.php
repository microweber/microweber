@extends('admin::layouts.app')

@section('content')

    <div class="row px-5">

        <h3 class="main-pages-title">{{ _e("Choose Page Type") }}</h3>



        <div class="row row-cards">

            <div class="col-md-4 pe-4">
                <h3 class="mb-3 font-weight-bold">{{_e("Clean Page")}} </h3>

                <div class="card">

                    <div class="card-body" style="padding: 13px; height: calc(50vh - 80px);">
                        <div class="create-page-clean d-flex justify-content-center align-items-center h-100">
                            <h4 class="font-weight-bold mb-0">{{_e("Empty Page")}}</h4>
                        </div>
                    </div>

                </div>
            </div>
        @foreach($layouts as $layout)
{{--            <div class="col-md-4 pe-3">--}}
{{--                <h3 class="mb-3 font-weight-bold">{{$layout['name']}} </h3>--}}

{{--                <div class="card">--}}
{{--                        @php--}}
{{--                            $iframe_start = site_url('new-content-preview-'. uniqid());--}}
{{--                        @endphp--}}
{{--                        @include('page::admin.page.iframe', [--}}
{{--                         'url'=>site_url($iframe_start . '?content_id=0&no_editmode=true&preview_layout=' . $layout['layout_file_preview']--}}
{{--                    )])--}}

{{--                </div>--}}
{{--            </div>--}}

        @endforeach

    </div>
    </div>

@endsection
