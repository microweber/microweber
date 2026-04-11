<!DOCTYPE html>
<html {!! lang_attributes() !!}>

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite CSS --}}

    <link rel="stylesheet" href="{{ asset('templates/bootstrap/dist/build/app.css') }}">


    <script>
        mw
            .iconLoader()
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('fontAwesome')
            .addIconSet('materialDesignIcons');
    </script>
</head>

<body class="{!! helper_body_classes() !!}">
<div class="main">
    <div class="navigation-holder">
        <module type="layouts" template="menus/skin-1" template-filter="menus" id="header-layout"/>
    </div>

    @yield('content')

    <module type="layouts" template="footers/skin-1" id="footer-layout" template-filter="footers" />

</div>
    {{-- Vite JS --}}
    <script src="{{ asset('templates/bootstrap/dist/build/app.js') }}"></script>

    {!! meta_tags_footer() !!}
</body>
