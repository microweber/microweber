@extends('admin::layouts.app')

@section('content')

    <div class="mx-5">
        <div class="row row-cards">
        @foreach($layouts as $layout)
            <div class="col-md-4">
                <b>
                    {{$layout['name']}}
                </b>
                <br />
                <div class="card">
                        @php
                            $iframe_start = site_url('new-content-preview-'. uniqid());
                        @endphp
                        @include('page::admin.page.iframe', [
                         'url'=>site_url($iframe_start . '?content_id=0&no_editmode=true&preview_layout=' . $layout['layout_file_preview']
                    )])

                </div>
            </div>

        @endforeach

    </div>
    </div>

@endsection
