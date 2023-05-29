@extends('admin::layouts.app')

@section('content')

    <style>
        .preview_frame_container.preview-in-self {
            height: calc(80vh - 80px);
        }
        .preview_frame_container {
            position: relative;
            overflow: hidden;
        }
        .preview_frame_container.preview-in-self iframe {
            height: calc(160vh - 160px) !important;
        }
        .preview_frame_container iframe {
            width: 200%;
            transform: scale(.5);
            top: 0;
            position: absolute;
            left: 0;
            transform-origin: 0 0;
            border: 1px solid silver;
            transition: .3s;
        }
    </style>


    <div class="mx-5">

        <div>
            <h3 class="font-weight-bold">Design</h3>
            <p>Change the design of your page</p>
        </div>

        <div>
            <div class="preview_frame_container preview-in-self">
               <iframe clas="preview_frame_small" src="{{site_url()}}"></iframe>
            </div>
        </div>


    </div>

@endsection
