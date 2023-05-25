@extends('admin::layouts.app')

@section('content')

    <div class="mx-5">
        <div class="row row-cards">
        @foreach($layouts as $layout)
            <div class="col-md-6">
                <b>
                    {{$layout['name']}}
                </b>
                <br />
                <div class="card">
                    <div class="card-body">
                        <iframe style="width:100%;height:300px"
                                src="{{site_url('download?no_editmode=true&preview_layout=' . $layout['layout_file'])}}">

                        </iframe>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
    </div>

@endsection
