{{--
  task-2026-05-17-a596d2 / AI-793 — admin-styled 404 page.
  Jira: https://microweber.atlassian.net/browse/AI-793

  Designer's R10-6 audit caught /admin/seo-settings + /admin/language +
  /admin/backup (plus any other unmatched /admin/* URL) returning
  plain-text "Page not found at url: ..." instead of an admin-styled
  page. AI-735 slice 1 (task-2026-05-16-256d49) added `admin` to
  the frontend catch-all exclusion regex so unmatched admin URLs
  propagate to Route::fallback() — but the fallback itself returned
  text/plain. AI-793 closes that gap.

  Standalone HTML page (NOT inside the Filament Livewire panel) —
  serving it from the fallback handler can't easily bootstrap the
  full panel context, and a minimal self-contained page is faster
  to render + impossible to break with downstream Filament changes.

  Mimics the Filament admin chrome enough to communicate "you are
  still in admin" — loads the same theme bundle, uses the same
  `.mw-admin-empty-state` typography + `.mw-table-empty-cta` button
  shipped earlier today (AI-789 + AI-731 lineage). Centered card
  with 404 heading, requested URL (escaped), short explanation, and
  primary CTA back to /admin/dashboard.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex,nofollow">
    <title>404 · {{ e(mw()->ui->brand_name() ?: 'Microweber') }}</title>

    {{-- Same theme bundle Filament admin loads — keeps fonts, colours,
         heroicon glyphs, and the .mw-admin-empty-state / .mw-table-empty-cta
         tokens consistent with the rest of the admin shell. --}}
    <link
        rel="stylesheet"
        href="{{ asset('vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css') }}"
    >
    <style>
        /* Minimal page-level reset so we don't depend on Filament's body
           styling running. Body fills the viewport + centers the card. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: var(--font-family, -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif);
            background-color: #f4f4f5;
            color: #111827;
        }
        .mw-admin-404-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 16px;
        }
        .mw-admin-404-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 48px 32px;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .mw-admin-404-code {
            font-size: 72px;
            font-weight: 700;
            line-height: 1;
            color: #6b7280;
            letter-spacing: -0.02em;
            margin: 0 0 8px;
        }
        .mw-admin-404-url {
            display: inline-block;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 13px;
            background-color: #f3f4f6;
            color: #6b7280;
            padding: 4px 10px;
            border-radius: 6px;
            margin: 8px 0 16px;
            word-break: break-all;
            max-width: 100%;
        }
        html.dark body { background-color: #0f172a; color: #f3f4f6; }
        html.dark .mw-admin-404-card { background-color: #1e293b; }
        html.dark .mw-admin-404-code { color: #9ca3af; }
        html.dark .mw-admin-404-url { background-color: #0f172a; color: #9ca3af; }
    </style>
</head>
<body class="fi-body fi-panel-admin">
    <div class="mw-admin-404-page">
        <div class="mw-admin-404-card mw-admin-empty-state">
            <p class="mw-admin-404-code">404</p>
            <h1 class="mw-admin-empty-state__heading">Page not found</h1>
            <p class="mw-admin-empty-state__body">
                We couldn't find the admin page you requested. It may have been moved, renamed, or it might not exist yet.
            </p>
            @if (!empty($requestedUrl))
                <div class="mw-admin-404-url" aria-label="Requested URL">{{ $requestedUrl }}</div>
            @endif
            <div class="mw-table-empty-cta-wrap mw-admin-empty-state__cta-wrap">
                <a
                    href="{{ url(mw_admin_prefix_url() ?: 'admin') }}"
                    class="mw-table-empty-cta mw-admin-empty-state__cta"
                    aria-label="Back to dashboard"
                >
                    Back to dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
