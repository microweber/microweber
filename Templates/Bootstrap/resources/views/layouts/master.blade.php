<!DOCTYPE html>
<html {!! lang_attributes() !!}>

{{-- AI-263 Phase B5 (cycle-185 2026-05-11) — `mw_require_jquery()`
     DROPPED. After 5 phases of the AI-263 P1 perf ticket:

       B1 (cycle-181) — conditional jQuery emission infrastructure +
                        vanilla CSRF fetch interceptor + opt-in flag
                        for templates that still needed jQuery
       B2 (cycle-182) — Slick → Swiper compatibility adapter (22
                        module skins benefit without per-skin edits)
       B3 (cycle-183) — Masonry / Datetimepicker / Chosen vanilla
                        adapters + Captcha direct cleanup
       B4 (cycle-184) — events.js:302 + Bootstrap collapseNav.js
                        vanilla rewrites
       B5 (cycle-185) — `mw.$` vanilla shim (this cycle): @core.js
                        now returns an MwDomCollection vanilla
                        wrapper when jQuery is absent; passes
                        through to jQuery when present (admin /
                        legacy). All chainable methods covered:
                        addClass/removeClass/hasClass/attr/on/off/
                        html/text/val/css/find/first/last/eq/parent/
                        children/closest/is/append/show/hide/data/
                        scrollTop/scroll/width/height/outerWidth/
                        outerHeight/clone/each/trigger.

     Net effect: every Bootstrap-template public page render no
     longer eagerly loads jquery.js (285KB) + jquery-ui.js (521KB)
     in <head> — saves 806KB of blocking JS.

     The cycle-181 conditional emission infrastructure still
     injects jQuery LATE (before </body>) on any page that
     renders a marker-bearing module skin (slick-slider,
     masonry, datetimepicker, chosen, data-mw-needs-jquery)
     so the cycle-182/183 adapters that attach to jQuery
     keep working on those pages.

     Admin / legacy admin paths (/admin/*, /api/*) preserve
     their existing eager jQuery loading per cycle-181's
     isAdminPath() check in ApijsScriptTag. --}}

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
    {{-- TASK-013 / TICKET-CY (cycle-58): public-site 44x44 tap-target sweep.
         Loaded after app.css so its touch-viewport rules win the cascade
         on phones and pointer-coarse devices. Mirrors the Filament admin
         pattern of a single dedicated mobile-touch.css file. --}}
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
{{-- TICKET-D (audit-test reply 2026-05-06): WCAG 2.4.1 Bypass Blocks.
     First focusable element on every public Bootstrap page; jumps past
     the global header/menu straight to <main>. Hidden until focused.
     #main-content carries tabindex="-1" so the focus jump works in
     every browser. Bootstrap 5's `visually-hidden-focusable` utility
     handles the hide-until-focus visual. --}}
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
