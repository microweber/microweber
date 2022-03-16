@extends('app::public.layout')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <style>
        .template-preview {
            width:100%;
            height:300px;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            margin-bottom: 15px;
        }
    </style>

    <div class="row my-5 d-flex align-items-center">
        @foreach($templates as $template)
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 mx-auto mt-4">
            <div class="card">
                <div class="card-body py-4">
                    <h3>{{$template['description']}}</h3>

                    @if($template['is_paid'])
                        <span class="badge badge-primary">FREE</span>
                    @else
                        <span class="badge badge-success">PREMIUM</span>
                    @endif


                    <div class="template-preview" style="background-image: url('{{$template['screenshot_link']}}');"></div>


                    <div class="text-center">
                        @if($template['is_paid'])
                            <a href="{{$template['buy_link']}}" class="btn btn-success" target="_blank">
                                BUY TEMPLATE
                            </a>
                        @endif


                        @if($template['demo_link'])
                            <a href="{{$template['demo_link']}}" class="btn btn-primary" target="_blank">
                                DEMO
                            </a>
                        @endif
                    </div>

                    <hr />
                   <small>v{{$template['version']}}</small>


                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
