<?php
/*
 * task-2026-05-17-f141ff / AI-794 — public auth layout (forgot-password + reset-password)
 *
 * Pre-AI-794 this layout emitted bare DOCTYPE/head/body via app::public.partials.header
 * + footer partials that load NO public template CSS — the rendered page was unstyled raw
 * HTML (designer's r11-forgot-password-light.png). Lineage: AI-757 (login parity)
 * + AI-793 (admin-404 styled card) + AI-789 (reusable empty-state chrome).
 *
 * Fix: extend the active template's master (Bootstrap fallback if active template has
 * no master) so the public template's CSS, header navigation, and footer load on every
 * auth page. Wrap auth content in .mw-auth-card chrome (semantic `<header>` for the
 * brand logo per WCAG + designer's Tier-3 probe) with brand logo capped at 64×64 max,
 * front-end form tokens, and primary CTA at brand blue #0d6efd (MwColors::Blue).
 *
 * Children should use `@section('auth_form') ... @endsection` (not `@section('content')`)
 * since this layout already owns the master's `content` slot.
 */

$templateViewsName = template_name();

$mwAuthExtends = 'templates.bootstrap::layouts.master';

if (isset($templateViewsName) && !empty($templateViewsName)) {
    $extendsCheckView = 'templates.' . $templateViewsName . '::layouts.master';
    if (view()->exists($extendsCheckView)) {
        $mwAuthExtends = $extendsCheckView;
    }
}

if (!isset(app()->ui->admin_logo_login_link) || app()->ui->admin_logo_login_link == false) {
    $mwAuthLogoLink = site_url();
} else {
    $mwAuthLogoLink = app()->ui->admin_logo_login_link;
}

$mwAuthLogoUrl = app()->ui->admin_logo_login();
?>
@extends($mwAuthExtends)

@section('content')

<style>
/* task-2026-05-17-f141ff / AI-794 — public auth surface chrome.
   Scoped to .mw-auth-card / .mw-auth-container so site-wide .form-control / .btn
   styling from the active template is untouched. */
.mw-auth-container { max-width: 480px; margin: 50px auto; padding: 20px; }
.mw-auth-header { text-align: center; margin-bottom: 24px; }
/* task-2026-05-17-6305c9 / AI-848 — Stage-2 sub-variant `CSS-rules-mutual-dependency`.
   Pre-fix: parent <a class="mw-auth-logo"> was inline-block with max-width:64px and
   NO defined width; img child had width:auto + max-width:100% (% of parent). Parent
   width depended on child intrinsic size; child width depended on parent — shrink-to-fit
   chicken-and-egg layout cycle settled at 0×0 (designer-measured at /forgot-password +
   /reset-password 1440 + 390). Compounding: source logo.svg carries viewBox="0 0 612 115.6"
   but NO explicit width=/height= attributes, so browsers treat it as having intrinsic
   ratio but no intrinsic SIZE — combined with shrink-to-fit parent, layout picks 0.
   Slice A fix (designer-preferred, preserves merchant aspect ratio): drop parent
   max-width constraint + set explicit height:64px on img + max-width:280px on img to
   bound wide brand marks. width:auto resolves from SVG ratio 612:115.6 ≈ 339px,
   clamped to 280 by max-width. Sister-rule to AI-803 .logo-module .logo-link inline-block
   parent-flex-context fix; same Stage-2 family, distinct sub-variant + distinct class. */
.mw-auth-header .mw-auth-logo { display: inline-block; }
.mw-auth-header .mw-auth-logo img { display: block; width: auto; height: 64px; max-width: 280px; }
.mw-auth-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); padding: 40px; }
.mw-auth-card h2,
.mw-auth-card h3 { font-size: 22px; font-weight: 600; color: #1f2937; margin: 0 0 20px; text-align: center; }
.mw-auth-card .form-group { margin-bottom: 16px; }
.mw-auth-card .form-label { display: block; margin-bottom: 8px; color: #374151; font-weight: 500; font-size: 14px; }
.mw-auth-card .form-control { width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; line-height: 1.4; background: #fff; color: #1f2937; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; box-sizing: border-box; }
.mw-auth-card .form-control:focus { outline: none; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15); }
.mw-auth-card .btn { display: inline-block; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; line-height: 1.2; cursor: pointer; transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out; border: 1px solid transparent; text-decoration: none; }
/* task-2026-05-17-06892a / AI-794a — CHANGE 1 absorbed (Stage-2 cascade-loss
   sibling to AI-697 v3 + AI-786 dark text + AI-810 flex-centring). Pre-CHANGE
   the .mw-auth-card .btn-primary single-class rule lost the cascade fight to
   the active template's .btn-primary (which ships with template-specific
   colour — often salmon #F4A261 in the Big2 template). Designer's runtime
   measurement at 1440 desktop showed rgb(244, 162, 97) instead of brand-blue.
   Canonical Stage-2 fix per LESSONS: compound selector at single-class
   specificity + !important on the colour declarations. The .mw-auth-card
   ancestor compound bumps specificity AND !important defeats any inline-style
   or framework-default that wins via higher specificity. */
.mw-auth-card .btn-primary { background-color: #0d6efd !important; color: #fff !important; border-color: #0d6efd !important; }
.mw-auth-card .btn-primary:hover,
.mw-auth-card .btn-primary:focus { background-color: #0b5ed7 !important; border-color: #0a58ca !important; color: #fff !important; }
.mw-auth-card .btn-link { background: transparent; color: #0d6efd; padding-inline: 0; }
.mw-auth-card .btn-link:hover,
.mw-auth-card .btn-link:focus { text-decoration: underline; color: #0a58ca; }
.mw-auth-card .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; line-height: 1.4; }
.mw-auth-card .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.mw-auth-card .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.mw-auth-card .text-danger,
.mw-auth-card .help-block { color: #991b1b; font-size: 13px; margin-top: 4px; }
.mw-auth-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
@media (max-width: 480px) {
    .mw-auth-container { margin: 16px auto; padding: 12px; }
    .mw-auth-card { padding: 24px; }
}
@media (prefers-reduced-motion: reduce) {
    .mw-auth-card .form-control,
    .mw-auth-card .btn { transition: none; }
}
</style>

<div class="mw-auth-container">
    <header class="mw-auth-header">
        <a href="<?php print e($mwAuthLogoLink); ?>" id="login-logo" class="mw-auth-logo">
            <img src="<?php print e($mwAuthLogoUrl); ?>" alt="{{ e(app()->ui->brand_name() ?: 'Logo') }}"/>
        </a>
    </header>

    <div class="mw-auth-card">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @hasSection('auth_form')
            @yield('auth_form')
        @endif
    </div>
</div>

@endsection
