<!DOCTYPE html>
<html {!! lang_attributes() !!}>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    {{-- 1. Shared Microweber CSS Framework — STANDALONE: it vendors Bootstrap 5
         (grid + reboot) and provides every semantic, token-driven component. The
         Base template depends on nothing from the Big or Bootstrap templates. --}}
    <link rel="stylesheet" href="{{ asset('packages/microweber-css-framework-bootstrap/css/mw-framework.css') }}">

    {{-- 2. Small Base-specific CSS (template font + touch-target safety). --}}
    <link rel="stylesheet" href="{{ asset('templates/base/css/base.css') }}">

    {{-- 3. Base template default design tokens (design-vars LAYER).
         Externalised from the old inline <style> block into a real file —
         templates/base/design-styles/design-vars.css — matching the Big template's
         design-styles/design-vars.css pattern. Same cascade position as before:
         after the framework + base.css (so brand tokens win over framework defaults)
         and before the saved colour scheme + child design + live_edit.css (so those
         override it). Child ("extra") templates ship their own design-vars.css with
         the same var names to override these defaults. --}}
    <link rel="stylesheet" href="{{ asset('templates/base/design-styles/design-vars.css') }}" id="mw-template-design-vars-defaults" type="text/css" media="all">

    {{-- 5. Saved colour scheme / "Customize styles" for the active template.
         The design-system picker persists the chosen --mw-* values to the
         `mw-template-<dir>` option group. Emit any saved --mw-* values as a final
         :root block so the saved scheme wins over the defaults above — on the
         published frontend, not only in the editor canvas. Safe no-op when nothing
         has been saved. --}}
    @php
        $mwSavedVars = [];
        try {
            $mwTplDir = app()->template_manager->get_config()['dir_name'] ?? null;
            if ($mwTplDir) {
                $mwSaved = get_options(['option_group' => 'mw-template-' . $mwTplDir]) ?: [];
                foreach ($mwSaved as $mwOpt) {
                    $k = $mwOpt['option_key'] ?? '';
                    $v = $mwOpt['option_value'] ?? '';
                    if (strpos($k, '--mw') === 0 && $v !== '' && $v !== null) {
                        $mwSavedVars[$k] = $v;
                    }
                }
            }
        } catch (\Throwable $e) {
            $mwSavedVars = [];
        }
    @endphp
    @if (!empty($mwSavedVars))
        <style id="mw-template-saved-design">
            :root {
                @foreach ($mwSavedVars as $mwK => $mwV)
                    {{ preg_replace('/[^a-z0-9\-]/i', '', $mwK) }}: {{ preg_replace('/[;{}<>]/', '', $mwV) }};
                @endforeach
            }
        </style>
    @endif

    {{-- 6. Active child template's baked design CSS (its Live Edit design, e.g.
         Art / Yoga / Barbershop). A thin child of Base ships its whole identity as
         resources/assets/css/live_edit.css → public templates/<name>/css/live_edit.css.
         Loaded LAST so the child's :root tokens + rules win over Base's defaults and
         the saved scheme. No-op on Base itself (it has no live_edit.css). --}}
    @php
        // The active (possibly child) template whose design CSS to load.
        // Prefer the CURRENT PAGE's own active_site_template so per-page designs
        // (e.g. a JSON design imported as a page under a drop-in template) load
        // their own CSS even when the global current_template differs — this is
        // what lets many different imported designs coexist on one site. Fall
        // back to the globally selected current_template, then template_name().
        $mwActiveTpl = '';
        if (defined('CONTENT_ID') && CONTENT_ID) {
            try {
                $mwCurContent = app()->content_manager->get_by_id(CONTENT_ID);
                if (!empty($mwCurContent['active_site_template'])
                    && strtolower((string) $mwCurContent['active_site_template']) !== 'inherit') {
                    $mwActiveTpl = (string) $mwCurContent['active_site_template'];
                }
            } catch (\Throwable $mwE) {
                // ignore — fall through to the global option
            }
        }
        if ($mwActiveTpl === '' && function_exists('get_option')) {
            $mwActiveTpl = (string) (get_option('current_template', 'template') ?: '');
        }
        if ($mwActiveTpl === '' && function_exists('template_name')) {
            $mwActiveTpl = (string) template_name();
        }
        $mwActiveTplLower = strtolower($mwActiveTpl);
        $mwChildCssRel = 'templates/' . $mwActiveTplLower . '/css/base.css';
        $mwChildCssFile = function_exists('public_path') ? public_path($mwChildCssRel) : '';
        // Child (extra) template design tokens: its own design-styles/design-vars.css
        // (the Big pattern, ported to Base children). Loaded as a LAYER after Base's
        // default design-vars.css and before the saved scheme + child base.css + live_edit.css.
        $mwChildVarsRel = 'templates/' . $mwActiveTplLower . '/design-styles/design-vars.css';
        $mwChildVarsFile = function_exists('public_path') ? public_path($mwChildVarsRel) : '';
        // Colour scheme: read from the per-template `template_css` option — the same
        // store the Live Edit colour editor writes via
        // TemplateCustomCss::liveEdit()->saveContent(). DB-backed, so it needs no
        // writable public path and no CDN round-trip, and it always reflects the latest
        // editor edits. base.css carries var() fallbacks, so the design renders regardless.
        $mwSchemeCss = '';
        if (function_exists('get_option') && $mwActiveTpl !== '' && $mwActiveTplLower !== 'base') {
            $mwSchemeCss = (string) get_option('template_css', 'template_' . $mwActiveTpl);
        }
    @endphp
    @if ($mwActiveTplLower !== '' && $mwActiveTplLower !== 'base' && $mwChildVarsFile && is_file($mwChildVarsFile))
        {{-- 4. Child (extra) template design tokens — its identity design-vars.css.
             Loaded after Base's default tokens (so the child's --mw-* win) and before
             the saved colour scheme + child base.css + live_edit.css (so those override). --}}
        <link rel="stylesheet" href="{{ asset($mwChildVarsRel) }}" id="mw-template-design-vars-child" type="text/css" media="all">
    @endif
    @if (trim($mwSchemeCss) !== '')
        {{-- Loaded after Base's default :root so the design scheme wins; carries the
             editor's live edits since it is the same option the editor saves to. --}}
        <style id="mw-template-design-vars" type="text/css">{!! $mwSchemeCss !!}</style>
    @endif
    @if ($mwActiveTplLower !== '' && $mwActiveTplLower !== 'base' && $mwChildCssFile && is_file($mwChildCssFile))
        <link rel="stylesheet" href="{{ asset($mwChildCssRel) }}" id="mw-template-design" type="text/css" media="all">
    @endif

    <script>
        mw.iconLoader()
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('fontAwesome')
            .addIconSet('materialDesignIcons');
    </script>

    @yield('head')
</head>

<body class="{!! helper_body_classes() !!}">

<a href="#main-content"
   class="visually-hidden-focusable position-absolute start-0 top-0 m-2 p-2 bg-primary text-white rounded shadow"
   style="z-index:2147483647;">{{ _e('Skip to main content', true) }}</a>

<div class="main mw-main">

    @if(empty($disable_header))
    <div class="navigation-holder">
        <module type="layouts" template="menus/skin-1" template-filter="menus" id="header-layout"/>
        {{-- Frontend language switcher. The multilanguage module renders nothing
             unless the site has 2+ active languages, so this is a safe no-op on
             single-language sites. --}}
        <div class="mw-header-language-switcher d-flex justify-content-end align-items-center px-3">
            <module type="multilanguage" template="default"/>
        </div>
    </div>
    @endif

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    @if(empty($disable_footer))
    <module type="layouts" template="footers/skin-19" id="footer-layout" template-filter="footers" />
    @endif

</div>

{{-- Frontend JS from our own framework (vendored Bootstrap 5 bundle: dropdowns,
     collapse, modal, …). Standalone — nothing loaded from Big/Bootstrap templates.
     Platform sliders/galleries come from the global frontend-assets bundle. --}}
<script src="{{ asset('packages/microweber-css-framework-bootstrap/js/mw-framework.js') }}"></script>

{!! meta_tags_footer() !!}

</body>
</html>