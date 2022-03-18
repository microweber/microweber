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
            height:500px;
            background-position: top;
            background-size: cover;
            background-repeat: no-repeat;
            margin-top: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>


    <div class="container mt-3">
       <div class="text-center pt-5">
           <h1 class="mb-3">Choose a template to start with</h1>
           <h5 class="lh-1_4">Each of our premium templates contains 450+ layouts in 20 different categories. <br> By buying a premium template you are saving time and money to create any type of website.</h5>
       </div>
        <div class="row justify-content-center py-3">
            <span class="btn btn-sm btn-primary rounded-0" onclick="$('.templates').fadeIn();">ALL</span>
            <span class="btn btn-sm btn-outlineprimary rounded-0" onclick="$('.templates').hide();$('.templates-free').fadeIn()">FREE</span>
            <span class="btn btn-sm btn-outlineprimary rounded-0" onclick="$('.templates').hide();$('.templates-paid').fadeIn()">PREMIUM</span>
        </div>
        <div class="row p-4 d-flex align-items-center">

        @foreach($templates as $template)
            <div class="hover-shadow-3 col-4  mt-4 templates @if($template['is_paid']) templates-paid @else templates-free @endif">
                <div class="card rounded-0 ">
                    <div class="card-body py-4">
                        <div class="d-flex">
                            <h5 class=" pl-0 col-6" >{{$template['description']}}</h5>

                            <div class="col-6 pr-0 text-right">
                                @if($template['is_paid'])
                                    <span class="badge badge-sm badge-primary rounded-0" style="background-color: #9c00ff; font-weight: normal; font-size: 12px;">PREMIUM LICENSE</span>
                                @else
                                    <span class="badge badge-sm badge-success rounded-0" style="font-weight: normal; font-size: 12px; ">FREE LICENSE</span>
                                @endif
                            </div>
                        </div>

                        <small class="text-muted d-block">v {{$template['version']}}</small>

                        <div class="template-preview" style="background-image: url('{{$template['screenshot_link']}}');"></div>

                        <div class="text-right">
                            @if($template['current_install'])
                                <a href="<?php echo site_url();?>?request_template={{$template['target-dir']}}" class="btn btn-sm btn-outline-success rounded-0">
                                    USE TEMPLATE
                                </a>
                            @else
                            <button onclick="installTemplate('{{$template['name']}}')" class="btn btn-sm btn-outline-success rounded-0">
                                INSTALL & USE TEMPLATE
                            </button>
                            @endif


                            @if($template['demo_link'])
                                <a href="{{$template['demo_link']}}" class="btn btn-sm btn-outline-primary rounded-0" target="_blank">
                                    DEMO
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $( ".card" ).hover(
                function() {
                    $(this).addClass('shadow-lg').css('cursor', 'pointer');
                }, function() {
                    $(this).removeClass('shadow-lg');
                }
            );

        });

    </script>
@endsection
