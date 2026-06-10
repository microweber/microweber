<!DOCTYPE html>
<html {!! lang_attributes() !!}>

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    <link rel="stylesheet" href="{{ asset('templates/big/dist/build/app.css') }}">

    {{--
        AI-531a (SOUL #56) / AI-518a + AI-603 runtime gap (tester
        evaluation 2026-05-15): public-touch.css carries the site-wide
        WCAG 2.5.5 44×44 touch-target floors (AI-516..AI-535 + AI-518a +
        AI-603). Until 2026-05-15 the file was loaded ONLY by the
        Bootstrap template's master.blade.php — non-Bootstrap templates
        (Big, Big1, etc.) inherited Bootstrap's general 44h rules via
        main.scss compilation but did NOT get the public-touch.css
        rules. Tester confirmed at 390×844 that the Big active-site
        header CTA still measured 137×38 px after the AI-518a fix
        landed in public-touch.css — root cause: this missing link
        load.

        Loading public-touch.css from this template's master.blade.php
        closes the runtime gap. Path points at the Bootstrap template
        asset dir because that's where the file is built (Vite source
        in Templates/Bootstrap/resources/assets/css/public-touch.css,
        served mirror in public/templates/bootstrap/css/public-touch.css).
        The Bootstrap template is part of every Microweber install
        (reference template), so the path is stable.

        Long-term follow-up (AI-531a): migrate public-touch.css to a
        template-agnostic location (e.g. packages/frontend-assets) so
        every template gets it without cross-template path coupling.
    --}}
    <link rel="stylesheet" href="{{ asset('templates/bootstrap/css/public-touch.css') }}">


{{--    @if(!lang_is_rtl())--}}
{{--        <link rel="stylesheet" href="{{ asset('templates/big/dist/build/app.css') }}">--}}
{{--    @else--}}
{{--        <link rel="stylesheet" href="{{ asset('templates/big/dist/build/app.css') }}">--}}

{{--        <link rel="stylesheet" href="{{ asset('templates/big/dist/build/app-rtl.css') }}">--}}
{{--    @endif--}}
    <script>




        mw.iconLoader()
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('fontAwesome')
            .addIconSet('materialDesignIcons');



    </script>



    <?php // print get_template_stylesheet(); ?>



    @yield('head')

</head>

<body class="{!! helper_body_classes() !!}">
{{-- TICKET-D2 (audit-test reply 2026-05-06): WCAG 2.4.1 Bypass Blocks.
     First focusable element on every public Big page; jumps past the
     global header/menu straight to <main>. The anchor id MUST stay
     `#main-content` to match the Bootstrap theme — agent-test asked
     for uniformity across themes (no per-theme bikeshedding) so any
     future tooling / reviewer can rely on a single selector. --}}
<a href="#main-content"
   class="visually-hidden-focusable position-absolute start-0 top-0 m-2 p-2 bg-primary text-white rounded shadow"
   style="z-index:2147483647;">{{ _e('Skip to main content', true) }}</a>

<div class="main">

    @if(empty($disable_header))
    <div class="navigation-holder">
        <module type="layouts" template="menus/skin-1" template-filter="menus" id="header-layout"/>
    </div>

    @endif

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>


    @if(empty($disable_footer))
    <module type="layouts" template="footers/skin-19" id="footer-layout" template-filter="footers" />
    @endif

</div>

<script src="{{ asset('templates/big/dist/build/app.js') }}"></script>

{!! meta_tags_footer() !!}

</body>
