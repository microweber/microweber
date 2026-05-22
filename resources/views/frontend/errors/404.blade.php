{{--
    task-2026-05-17-c54f1f / AI-795 — frontend 404 styled card (closes AI-755 in the same ship).

    Mirrors AI-793 admin 404 propagation pattern, adapted for the public frontend.
    Lineage: AI-755 (frontend placeholder 200 instead of 404 — original ticket)
             + AI-793 (admin 404 propagation — sister surface, same shape)
             + AI-794 (auth-flow chrome refresh — same .mw-auth-card design language).

    Why this view: pre-fix, unknown frontend URLs (/register, /this-page-does-not-exist,
    any garbage slug) fell through FrontendController's $show_404_to_non_admin branch.
    The render pipeline set status 404 BUT rendered the `clean.php` template with the
    default $page array — body contained `My title / My text content.` placeholder,
    no <meta name="robots" content="noindex,nofollow">. Google indexed every
    Microweber install's stub.

    Fix: if no template-level 404.php exists, render THIS view inside the active
    template's master (Bootstrap fallback), with explicit noindex+nofollow meta,
    a styled "Page not found" card matching the auth-shell visual language, and
    a "Back to homepage" CTA.

    Tier-3 probe (designer dispatch):
        for (const u of ['/register', '/this-page-does-not-exist', '/some/random/slug']) {
            const r = await fetch(u);
            expect(r.status).toBe(404);
            const html = await r.text();
            expect(html).toContain('noindex,nofollow');
            expect(html).not.toContain('My title');
        }
--}}
@extends($extendsView ?? 'templates.bootstrap::layouts.master')

@push('meta')
<meta name="robots" content="noindex,nofollow">
@endpush

@section('content')

<style>
/* task-2026-05-17-c54f1f / AI-795 — frontend 404 card.
   Scoped to .mw-frontend-404 so the active template's site-wide
   .form-control / .btn / heading rules stay untouched.
   task-2026-05-22-3bc697 / AI-1036 — color: #fff !important on primary CTA.
   AI-877 (a:not(.btn):not(.navbar-brand):not([class*="btn-"]) { color: var(--color-primary) })
   has specificity (0,3,1) which beats the original .mw-frontend-404__cta--primary (0,1,0),
   painting the button text #0d6efd on a #0d6efd background. !important restores white text. */
.mw-frontend-404 { max-width: 560px; margin: 60px auto; padding: 20px; text-align: center; }
.mw-frontend-404__card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); padding: 48px 32px; }
.mw-frontend-404__status { display: inline-block; font-size: 14px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; }
.mw-frontend-404__heading { font-size: 28px; font-weight: 600; color: #1f2937; margin: 0 0 16px; }
.mw-frontend-404__body { font-size: 16px; line-height: 1.5; color: #4b5563; margin: 0 0 24px; }
.mw-frontend-404__url { display: inline-block; padding: 6px 10px; border-radius: 6px; background: #f3f4f6; color: #374151; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 13px; word-break: break-all; max-width: 100%; }
.mw-frontend-404__actions { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
.mw-frontend-404__cta { display: inline-block; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; line-height: 1.2; text-decoration: none; border: 1px solid transparent; transition: background-color 0.15s ease-in-out; }
.mw-frontend-404__cta--primary { background: #0d6efd; color: #fff !important; border-color: #0d6efd; }
.mw-frontend-404__cta--primary:hover,
.mw-frontend-404__cta--primary:focus { background: #0b5ed7; border-color: #0a58ca; color: #fff !important; text-decoration: none; }
@media (max-width: 480px) {
    .mw-frontend-404 { margin: 24px auto; padding: 12px; }
    .mw-frontend-404__card { padding: 32px 20px; }
    .mw-frontend-404__heading { font-size: 22px; }
}
@media (prefers-reduced-motion: reduce) {
    .mw-frontend-404__cta { transition: none; }
}
</style>

{{-- Belt-and-braces noindex: also emit the meta directly inside the page body
     so crawlers that parse partial HTML still see it. Templates that don't
     wire up the `meta` push stack (most don't) still get the noindex signal. --}}
<meta name="robots" content="noindex,nofollow">

<div class="mw-frontend-404">
    <div class="mw-frontend-404__card">
        <span class="mw-frontend-404__status">{{ _e('404 — page not found', true) }}</span>
        <h1 class="mw-frontend-404__heading">{{ _e('We can\'t find that page.', true) }}</h1>
        <p class="mw-frontend-404__body">{{ _e('The address you visited doesn\'t match any page on this site. It may have been moved, renamed, or never existed.', true) }}</p>

        @if (!empty($requestedUrl))
            <p class="mw-frontend-404__body">
                <span class="mw-frontend-404__url">{{ $requestedUrl }}</span>
            </p>
        @endif

        <div class="mw-frontend-404__actions">
            <a href="{{ site_url() }}" class="mw-frontend-404__cta mw-frontend-404__cta--primary">{{ _e('Back to homepage', true) }}</a>
        </div>
    </div>
</div>

@endsection
