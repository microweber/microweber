<!DOCTYPE html>
<html {!! lang_attributes() !!}>

@php
    if (function_exists('mw_require_jquery')) {
        mw_require_jquery();
    }
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! meta_tags_head() !!}

    {{-- Plain template: ONLY the minimal structural CSS (grid + utils + reset).
         Everything visual (colours, fonts, components) comes from the AI agent's
         global custom CSS / Live-Edit edits, loaded after this by meta_tags_head. --}}
    <link rel="stylesheet" href="{{ asset('templates/plain/css/plain.css') }}">

    <script>
        mw.iconLoader()
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('fontAwesome')
            .addIconSet('materialDesignIcons');
    </script>
</head>

<body class="{!! helper_body_classes() !!}">
<a href="#main-content"
   class="mw-skip-link"
   style="position:absolute;left:-9999px;top:0;z-index:2147483647;padding:8px;">{{ _e('Skip to main content', true) }}</a>

<div class="main mw-main">
    <div class="navigation-holder">
        <module type="layouts" template="menus/skin-1" template-filter="menus" id="header-layout"/>
    </div>

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <module type="layouts" template="footers/skin-1" id="footer-layout" template-filter="footers" />
</div>

{!! meta_tags_footer() !!}
</body>
</html>
