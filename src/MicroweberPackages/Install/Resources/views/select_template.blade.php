@extends('app::public.layout')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <script>
        function installTemplate(name)
        {
            installTemplateDialog = mw.dialog({
                content: '<div>Loading...</div>',
                title: 'Installing template',
                width: 900,
                id: 'mw_install_template'
            });

            $.ajax({
                url: mw.settings.site_url + '?install_template_modal=' + name,
                type: "GET",
                success: function (html) {
                    installTemplateDialog.content(html);
                    installTemplateDialog.center();
                }
            });

            return name;
        }
    </script>
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

    <div class="row my-5 p-4 d-flex align-items-center">

        @foreach($templates as $template)
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 mx-auto mt-4">
            <div class="card">
                <div class="card-body py-4">
                    <h5>{{$template['description']}}</h5>
                    <small>v{{$template['version']}}</small>

                   <div class="float-right">
                       @if($template['is_paid'])
                           <span class="badge badge-sm badge-primary rounded-0" style="background-color: #9c00ff;">PREMIUM LICENSE</span>
                       @else
                           <span class="badge badge-sm badge-success rounded-0">FREE LICENSE</span>
                       @endif
                   </div>

                    <div class="template-preview" style="background-image: url('{{$template['screenshot_link']}}');"></div>

                    <div class="text-right">
                        @if($template['current_install'])
                            <a href="<?php echo site_url();?>?request_template={{$template['target-dir']}}" class="badge badge-md btn-success rounded-0">
                                USE TEMPLATE
                            </a>
                        @else
                        <button onclick="installTemplate('{{$template['name']}}')" class="badge badge-md btn-success rounded-0">
                            INSTALL & USE TEMPLATE
                        </button>
                        @endif


                        @if($template['demo_link'])
                            <a href="{{$template['demo_link']}}" class="badge badge-md btn-primary rounded-0" target="_blank">
                                DEMO
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
