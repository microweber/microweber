@extends('admin::layouts.app')

@section('content')


    @if(isset($layout) && $layout)
        @include('page::admin.page.edit', ['layout' => $layout])
    @else
    <div class="row px-5">

        <h3 class="main-pages-title">{{ _e("Choose Page Type") }}</h3>

        <div class="row row-cards">

        <div class="col-md-4 pe-5 pb-3">
            <h3 class="mb-3 font-weight-bold">{{_e("Clean Page")}} </h3>

            <a href="{{route('admin.page.create')}}?layout=clean" class="card card-link card-link-pop">

                <div class="card-body" style="padding: 13px; height: calc(50vh - 80px);">
                    <div class="create-page-clean d-flex justify-content-center align-items-center h-100">
                        <h4 class="font-weight-bold mb-0">{{_e("Empty Page")}}</h4>
                    </div>
                </div>

            </a>
        </div>

        @foreach($allLayouts as $layout)

            @php
                if($layout['layout_file'] == 'clean.php') {
                    continue;
                }
            @endphp

            <div class="col-md-4 pe-5 pb-3">
                <h3 class="mb-3 font-weight-bold">{{$layout['name']}} </h3>
                <div class="card">
                    @php
                            $iframe_start = site_url('new-content-preview-'. uniqid());
                        @endphp
                        @include('page::admin.page.iframe', [
                         'url'=>site_url($iframe_start . '?content_id=0&no_editmode=true&preview_layout=' . $layout['layout_file_preview']
                    )])
                    <div class="p-2 text-center">
                        <a href="{{route('admin.page.create')}}?layout={{$layout['layout_file_preview']}}" class="btn btn-outline-primary">
                            Create
                        </a>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
    </div>
    @endif

@endsection
