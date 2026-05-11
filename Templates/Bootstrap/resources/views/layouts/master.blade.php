<!DOCTYPE html>
<html {!! lang_attributes() !!}>

@php
    /*
     * Cycle-188 EMERGENCY ROLLBACK (2026-05-11):
     * `mw_require_jquery()` RESTORED on Bootstrap public pages
     * per customer request. Templates rely on jQuery and need it
     * to function correctly — overrides the AI-263 Phase B5
     * cycle-185 drop.
     *
     * AI-263 lineage:
     *   B1 (cycle-181) — conditional jQuery emission + vanilla CSRF
     *   B2 (cycle-182) — Slick → Swiper adapter
     *   B3 (cycle-183) — Masonry / Datetimepicker / Chosen adapters
     *   B4 (cycle-184) — events.js + collapseNav.js vanilla
     *   B5 (cycle-185) — mw.$ vanilla shim + mw_require_jquery DROPPED
     *                    (saved 806KB on every Bootstrap page render)
     *   C  (cycle-186) — Phase C network-payload baseline confirmed
     *                    the 806KB drop + 57% public-page JS reduction
     *   Cycle-188 (this) — EMERGENCY ROLLBACK restores call per
     *                      customer request; the 806KB returns but
     *                      adapter infrastructure stays additive.
     *
     * The full AI-263 B1-B5 + C infrastructure is RETAINED — all the
     * adapters (Swiper, Masonry, Datetimepicker, Chosen, Captcha
     * vanilla) + `mw.$` hybrid wrapper + vanilla CSRF fetch
     * interceptor + collapseNav vanilla rewrite + events.js native
     * addEventListener changes ALL continue to ship. Those are
     * additive improvements that work transparently with jQuery
     * present — they fall through to the real jQuery when it's
     * loaded (as it now is again).
     *
     * Future re-removal path (if customer reconsiders):
     *   1. Verify template features one-by-one with jQuery absent
     *   2. Drop `mw_require_jquery()` from this file again
     *   3. The cycle-185 vanilla shim infrastructure handles the rest
     *
     * See cycle-185 commit fbee6c6009 for the original drop +
     * cycle-188 rollback for full context.
     */
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
