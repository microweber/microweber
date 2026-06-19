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

    {{-- 3. Base template default design tokens. Loaded after the framework so the
         template's brand wins over the framework defaults. The saved colour scheme
         (block 4) overrides these in turn. --}}
    <style>
        :root {
            --mw-primary-color: #f4a261;
            --mw-secondary-color: #6c757d;
            --mw-background-color: #ffffff;
            --mw-section-background-color: #ffffff;
            --mw-body-color: #000000;
            --mw-text-dark: #212529;
            --mw-text-light: #f8f9fa;
            --mw-body-font-family: 'Jost', sans-serif;
            --mw-heading-font-family: 'Jost', sans-serif;
            --mw-body-font-size: 16px;
            --mw-paragraph-size: 16px;
            --mw-paragraph-color: #000000;
            --mw-heading-color: #000000;
            --mw-line-height: 1.5;
            --mw-letter-spacing: 0;
            --mw-font-weight: 400;
            --mw-text-transform: none;
            --mw-link-color: #f4a261;
            --mw-link-hover-color: #e76f51;
            --mw-btn-background-color: #f4a261;
            --mw-btn-background-hover-color: #e76f51;
            --mw-btn-text-color: #ffffff;
            --mw-btn-text-hover-color: #ffffff;
            --mw-btn-border-color: #f4a261;
            --mw-btn-border-radius: 33px;
            --mw-btn-border-size: 2px;
            --mw-btn-border-style: solid;
            --mw-btn-padding-block: 15px;
            --mw-btn-padding-inline: 24px;
            --mw-btn-font-size: 16px;
            --mw-btn-secondary-background-color: #FF5E00;
            --mw-btn-secondary-background-hover-color: #f4a261;
            --mw-btn-secondary-text-color: #ffffff;
            --mw-btn-secondary-text-hover-color: #ffffff;
            --mw-btn-secondary-border-color: #f4a261;
            --mw-btn-outline-background-color: transparent;
            --mw-btn-outline-background-hover-color: #f4a261;
            --mw-btn-outline-text-color: #f4a261;
            --mw-btn-outline-text-hover-color: #ffffff;
            --mw-btn-outline-border-color: #f4a261;
            --mw-header-background-color: #ffffff;
            --mw-header-link-color: #f4a261;
            --mw-header-link-hover-color: #f4a261;
            --mw-header-link-background-hover-color: transparent;
            --mw-header-padding-block: 10px;
            --mw-header-font-size: 16px;
            --mw-top-header-background-color: #000000;
            --mw-top-header-primary-color: #f4a261;
            --mw-top-header-link-color: #f4a261;
            --mw-top-header-link-hover-color: #f4a261;
            --mw-top-header-button-background-color: #f4a261;
            --mw-top-header-button-background-hover-color: #f4a261;
            --mw-top-header-button-text-color: #ffffff;
            --mw-top-header-button-text-hover-color: #ffffff;
            --mw-footer-background-color: #ffffff;
            --mw-footer-link-color: #f4a261;
            --mw-footer-hover-link-color: #f4a261;
            --mw-footer-text-color: #000000;
            --mw-footer-primary-color: #f4a261;
            --mw-footer-font-size: 16px;
            --mw-footer-button-background-color: #f4a261;
            --mw-footer-button-background-hover-color: #f4a261;
            --mw-footer-button-text-color: #ffffff;
            --mw-footer-button-text-hover-color: #ffffff;
            --mw-form-control-border-radius: 0px;
            --mw-form-control-border-size: 1px;
            --mw-form-control-border-style: solid;
            --mw-form-control-border-color: #ced4da;
            --mw-form-control-padding-block: 15px;
            --mw-form-control-padding-inline: 15px;
            --mw-form-control-placeholder-color: #6c757d;
            --mw-form-control-background: #ffffff;
            --mw-form-control-text-color: #000000;
            --mw-form-control-text-hover-color: #000000;
            --mw-form-label-color: #000000;
            --mw-form-label-font-size: 16px;
            --mw-text-on-dark-background-color: #ffffff;
            --mw-heading-one: 64px;
            --mw-heading-two: 52px;
            --mw-heading-three: 44px;
            --mw-heading-four: 32px;
            --mw-heading-five: 24px;
            --mw-heading-six: 20px;
        }
    </style>

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