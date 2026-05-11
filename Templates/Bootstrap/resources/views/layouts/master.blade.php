<!DOCTYPE html>
<html {!! lang_attributes() !!}>

@php
    // Load jQuery + jQuery UI for templates that need them.
    if (function_exists('mw_require_jquery')) {
        mw_require_jquery();
    }
@endphp

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
    {{-- Public-site 44x44 tap-target floor — loaded after app.css so its touch-viewport rules win the cascade. --}}
    <link rel="stylesheet" href="{{ asset('templates/bootstrap/css/public-touch.css') }}">


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
{{-- WCAG 2.4.1 "Bypass Blocks" skip link — first focusable element on every public page;
     jumps past header/menu straight to <main>. Hidden until focused. --}}
<a href="#main-content"
   class="visually-hidden-focusable position-absolute start-0 top-0 m-2 p-2 bg-primary text-white rounded shadow"
   style="z-index:2147483647;">{{ _e('Skip to main content', true) }}</a>
<div class="main">
    <div class="navigation-holder">
        <module type="layouts" template="menus/skin-1" template-filter="menus" id="header-layout"/>
    </div>

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <module type="layouts" template="footers/skin-1" id="footer-layout" template-filter="footers" />

</div>
    {{-- Vite JS --}}
    <script src="{{ asset('templates/bootstrap/dist/build/app.js') }}"></script>

    {!! meta_tags_footer() !!}
</body>
