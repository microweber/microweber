<!DOCTYPE html>
<html {!! lang_attributes() !!}>

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    {{-- Vite CSS --}}

    @if(!lang_is_rtl())
    {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app.scss') }}
    @else
    {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app-rtl.scss') }}
    @endif


</head>

<body class="{!! helper_body_classes() !!}">
<module type="logo" id="header-logo-loading" logo-name="header-logo" class="w-100"/>

<div class="main">
    <div class="navigation-holder">
        <module type="layouts" template="menus/skin-1" template-filter="menus" id="header-layout"/>
    </div>



    @yield('content')


    <module type="layouts" template="footers/skin-4" id="footer-layout" template-filter="footers" />


</div>

    {{-- Vite JS --}}
    {{ template_vite('templates/bootstrap/dist', 'resources/assets/js/app.js') }}

    {!! meta_tags_footer() !!}
</body>
