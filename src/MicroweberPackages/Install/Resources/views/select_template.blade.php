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
            background-position: top;
            background-size: cover;
            background-repeat: no-repeat;
            margin-top: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>


    <div class="text-center mt-3">
        <h1>Select Template To Startup Your Website</h1>
        <p>Select your favorite design for your business or blog.</p>
    </div>

    <div class="row my-5 d-flex align-items-center">

        @foreach($templates as $template)
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 mx-auto mt-4">
            <div class="card">
                <div class="card-body py-4">
                    <h3>{{$template['description']}}</h3>

                    @if($template['is_paid'])
                        <span class="badge badge-success">PREMIUM LICENSE</span>
                    @else
                        <span class="badge badge-primary">FREE LICENSE</span>
                    @endif


                    <div class="template-preview" style="background-image: url('{{$template['screenshot_link']}}');"></div>


                    <div class="text-center">

                        @if($template['current_install'])
                            <a href="<?php echo site_url();?>?request_template={{$template['target-dir']}}" class="btn btn-outline-success">
                                USE TEMPLATE
                            </a>
                        @else
                        <button class="btn btn-outline-success">
                            INSTALL & USE TEMPLATE
                        </button>
                        @endif


                        @if($template['demo_link'])
                            <a href="{{$template['demo_link']}}" class="btn btn-outline-primary" target="_blank">
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
