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

        <div class="card mb-4" x-data="{previewUrl: '{{site_url()}}'}">
            <div class="card-body">
             <div>
                 <b>Page</b>
                 <select x-model="previewUrl" class="form-control">
                     @foreach($allLayouts as $layout)
                         <option value="{{$layout['layout_file_preview_url']}}">{{$layout['name']}}</option>
                     @endforeach
                 </select>
             </div>
            <div class="mt-3">

                <button type="button" @click="" class="btn btn-primary">Next Layout</button>
                <button type="button" class="btn btn-primary">Previous Layout</button>

                <div x-html="previewUrl"></div>
                <div class="preview_frame_container preview-in-self">
                    <iframe class="preview_frame_small" :src="previewUrl"></iframe>
                </div>
            </div>
            </div>
        </div>



    </div>

@endsection
