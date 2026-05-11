<!DOCTYPE html>
<html {!! lang_attributes() !!}>

@php
    /*
     * AI-263 Phase B4 (cycle-184 2026-05-11) — opt-in to eager
     * jQuery loading STILL REQUIRED (partial-progress).
     *
     * Cycle-184 refactored two of the three Phase B4 jQuery
     * dependencies:
     *
     *   1. `packages/frontend-assets/resources/assets/core/events.js`
     *      line 302 — `$(window).on('hashchange load', ...)` is
     *      now `window.addEventListener('hashchange'/'load',
     *      ...)`.
     *
     *   2. `Templates/Bootstrap/resources/assets/js/collapseNav.js`
     *      — full vanilla rewrite (211 lines), no `$` references.
     *      Exposes `window.MwCollapseNav(selector, config)`.
     *
     * REMAINING BLOCKER (deferred to Phase B5+):
     *
     *   3. `frontend.js` uses `mw.$()` (the jseldom-jquery
     *      wrapper at libs/jseldom/jseldom-jquery.js) at
     *      module-init time throughout core/events.js,
     *      core/ajax.js, core/forms.js, etc. The wrapper itself
     *      throws `ReferenceError: jQuery is not defined` when
     *      jQuery is missing. Refactoring `mw.$` to be
     *      jQuery-optional requires either swapping it for a
     *      vanilla DOM wrapper OR guarding every `mw.$` call
     *      site (~30 across core/ + apis/). Multi-cycle work.
     *
     * Until Phase B5 lands, this template MUST still call
     * `mw_require_jquery()` so jQuery loads in `<head>` before
     * frontend.js's module-init code runs.
     *
     * Net cycle-184 progress: 2/3 of the Phase B4 work done.
     * Phase B5 (mw.$ refactor) will deliver the actual 806KB
     * savings on every Bootstrap-template page.
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
