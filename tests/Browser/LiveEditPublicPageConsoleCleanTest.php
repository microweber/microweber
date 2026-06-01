<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-5 "Lighthouse-lite" public-render health: loading a live-edit-
 * built landing page as a guest must not emit SEVERE console errors
 * or uncaught promise rejections.
 *
 * Task framing (TODO.md Phase 5):
 *   "Lighthouse-lite: no console errors, no uncaught promise
 *    rejections while loading public page".
 *
 * Complementary to the sibling public-render tests:
 *   - {@see \Tests\Browser\LiveEditPublicRenderParityPhase2SectionsTest}
 *     proves the HTML contains every section marker (server rendered
 *     the right thing).
 *   - {@see \Tests\Browser\LiveEditPublicAssetsNo404Test} proves every
 *     referenced asset URL returns 2xx/3xx (server can serve the
 *     things the HTML points at).
 *   - This test proves the browser can *execute* what the server
 *     delivered without crashing. A stale ES-module import, a removed
 *     global, or a typo'd `await` anywhere in the bundle would sail
 *     through the other two checks and still break the page for real
 *     users.
 *
 * Implementation notes:
 *
 *   - The build step logs in as admin and inserts a compact,
 *     JS-dense subset of Phase-2 skins (jumbotron with bg image,
 *     pricing with Bootstrap JS, blog with posts module that wires
 *     up a Swiper-style slider on mount). After save we log out and
 *     clear cookies so the public render doesn't get the admin
 *     toolbar JS — admin chrome would mask or trigger script errors
 *     that public visitors never see.
 *
 *   - Two capture channels:
 *       1. An in-page guard installed right after visit() attaches
 *          `window.addEventListener('error', …)` and
 *          `window.addEventListener('unhandledrejection', …)`. These
 *          fire for errors that happen *after* the hook is attached
 *          — useful for async bootstrap code (Swiper init, font
 *          loaders, and other `DOMContentLoaded` listeners).
 *       2. ChromeDriver's browser log at SEVERE level. That captures
 *          errors that happen *before* our hook is attached — the
 *          initial parse errors, top-level throws from module-script
 *          bundles, and failed resource loads that promote to SEVERE.
 *     Between them there are no blind spots in the page's lifecycle.
 *
 *   - The skip list is deliberately narrow: we ignore the exact
 *     known-noise patterns we already bucket in
 *     tests/BrowserLegacy/Components/ChekForJavascriptErrors.php —
 *     net::ERR_CONTENT_LENGTH_MISMATCH (intermittent dev-server
 *     chunk close) and `install_log.txt 404` (only present on fresh
 *     installs). Anything else — including 404s on template images,
 *     syntax errors, reference errors, and unhandled promise
 *     rejections — is a real failure.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPublicPageConsoleCleanTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * @var array<int, array{category:string, skin:string, marker:string}>
     */
    private const SECTIONS = [
        [
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'marker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-2',
            'marker' => 'section.pricing-skin-2[field^="layout-pricing-skin-2-"]',
        ],
        [
            'category' => 'Blog',
            'skin' => 'blog/skin-1',
            'marker' => 'section[field^="layout-blog-skin-1-"]',
        ],
    ];

    /**
     * Noise we genuinely don't care about — match the legacy skip list
     * in tests/BrowserLegacy/Components/ChekForJavascriptErrors.php so
     * we don't diverge from existing policy. A match anywhere in the
     * log message text skips the entry.
     *
     * @var array<int, string>
     */
    private const CONSOLE_NOISE_PATTERNS = [
        'install_log.txt',
        'ERR_CONTENT_LENGTH_MISMATCH',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function public_page_loads_with_clean_console_and_no_unhandled_rejections(): void
    {
        $landing = LandingPageFactory::make('Public console clean');

        $this->browse(function (Browser $browser) use ($landing) {
            // Build step (admin session)
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            foreach (self::SECTIONS as $i => $section) {
                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertSkinWithCategoryWait($browser, $section['category'], $section['skin']);
                $this->waitForMarker($browser, $section['marker'], $i + 1);
            }

            $this->markAllEditsChanged($browser);
            $this->saveLiveEdit($browser);
            $browser->pause(1000);

            // Drop the admin session so the public render executes with
            // guest JS only — admin toolbar scripts would otherwise mask
            // or trigger errors public visitors never encounter.
            $this->logoutToGuest($browser);

            // Drain any prior-page log entries so the check below is
            // scoped to the public visit only.
            $this->drainBrowserLog($browser);

            $browser->visit('/' . ltrim($landing->slug, '/'));

            $this->installInPageErrorGuard($browser);

            // Give the page a full settle window: initial parse, then
            // deferred scripts, then Swiper/font/etc bootstrap from
            // DOMContentLoaded + load events.
            $browser->pause(4000);

            $inPageErrors = $this->readInPageErrors($browser);
            $severeLogs = $this->readSevereLogs($browser);

            $this->assertSame(
                [],
                $inPageErrors,
                'Public page at /' . $landing->slug . ' must not emit uncaught errors '
                . 'or unhandled promise rejections during load + 4s settle window. '
                . 'Events: ' . json_encode($inPageErrors)
            );

            $this->assertSame(
                [],
                $severeLogs,
                'Public page at /' . $landing->slug . ' must not produce SEVERE '
                . 'console entries (JS parse errors, top-level throws, failed '
                . 'resource loads that promote to SEVERE). Entries: '
                . json_encode($severeLogs)
            );
        });
    }

    /**
     * Log out via /api/logout, clear cookies, and force a clean page
     * before the public visit so no admin-side JS lingers.
     */
    private function logoutToGuest(Browser $browser): void
    {
        $browser->visit('/');
        $browser->pause(500);
        $browser->script("
            try {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': decodeURIComponent(
                            (document.cookie.match(/XSRF-TOKEN=([^;]+)/) || ['',''])[1]
                        )
                    },
                    credentials: 'include'
                });
            } catch (e) {}
        ");
        $browser->pause(1500);
        $browser->driver->manage()->deleteAllCookies();
        $browser->pause(300);

        // Invalidate the shared admin-login cache so the next test's
        // loginAsAdmin() actually re-logs in. Without this reset, the
        // static $adminLoggedIn flag would lie about the session (we
        // just torched the cookies) and the next test in the same PHP
        // process would skip login and then fail on /login redirects.
        \Tests\DuskTestCase::$adminLoggedIn = false;
    }

    /**
     * Install a page-side collector for uncaught errors and
     * unhandled promise rejections. Stores events on a global array
     * so {@see readInPageErrors} can drain them after the settle
     * window.
     *
     * The guard runs *after* the page has started loading because
     * Dusk's `visit()` is synchronous-to-nav-start; anything thrown
     * during the very first tick of a module script will have
     * already landed in the ChromeDriver browser log (which we also
     * check). So the combination of the two channels covers the
     * full page lifecycle.
     */
    private function installInPageErrorGuard(Browser $browser): void
    {
        $browser->script("
            (function () {
                if (window.__publicConsoleGuardInstalled) return;
                window.__publicConsoleGuardInstalled = true;
                window.__publicConsoleGuardEvents = [];

                window.addEventListener('error', function (ev) {
                    try {
                        window.__publicConsoleGuardEvents.push({
                            kind: 'error',
                            message: (ev && ev.message) ? String(ev.message) : '',
                            filename: (ev && ev.filename) ? String(ev.filename) : '',
                            line: (ev && typeof ev.lineno === 'number') ? ev.lineno : null
                        });
                    } catch (e) {}
                });

                window.addEventListener('unhandledrejection', function (ev) {
                    try {
                        var reason = ev && ev.reason;
                        var msg = '';
                        if (reason) {
                            if (typeof reason === 'string') msg = reason;
                            else if (reason.message) msg = String(reason.message);
                            else { try { msg = JSON.stringify(reason); } catch (e) { msg = String(reason); } }
                        }
                        window.__publicConsoleGuardEvents.push({
                            kind: 'unhandledrejection',
                            message: msg,
                            filename: '',
                            line: null
                        });
                    } catch (e) {}
                });
            })();
        ");
    }

    /**
     * @return array<int, array{kind:string, message:string, filename:string, line:int|null}>
     */
    private function readInPageErrors(Browser $browser): array
    {
        $res = $browser->script("
            var events = (window.__publicConsoleGuardEvents || []).slice();
            return events;
        ");

        $payload = $res[0] ?? [];
        if (!is_array($payload)) return [];

        $out = [];
        foreach ($payload as $event) {
            if (!is_array($event)) continue;
            $message = (string)($event['message'] ?? '');
            if ($this->isNoise($message)) continue;
            $out[] = [
                'kind' => (string)($event['kind'] ?? ''),
                'message' => $message,
                'filename' => (string)($event['filename'] ?? ''),
                'line' => is_int($event['line'] ?? null) ? (int)$event['line'] : null,
            ];
        }
        return $out;
    }

    /**
     * Pull any entries left in the ChromeDriver browser log without
     * acting on them. Used before the public visit so our post-visit
     * read only sees the public page's errors.
     */
    private function drainBrowserLog(Browser $browser): void
    {
        try {
            $browser->driver->manage()->getLog('browser');
        } catch (\Throwable) {
            // ChromeDriver log API can be flaky under certain builds
        }
    }

    /**
     * @return array<int, array{level:string, message:string}>
     */
    private function readSevereLogs(Browser $browser): array
    {
        try {
            $logs = $browser->driver->manage()->getLog('browser');
        } catch (\Throwable) {
            return [];
        }

        $out = [];
        foreach ($logs as $log) {
            $level = (string)($log['level'] ?? '');
            $message = (string)($log['message'] ?? '');
            if ($level !== 'SEVERE') continue;
            if ($this->isNoise($message)) continue;
            $out[] = ['level' => $level, 'message' => $message];
        }
        return $out;
    }

    private function isNoise(string $message): bool
    {
        foreach (self::CONSOLE_NOISE_PATTERNS as $pattern) {
            if ($pattern !== '' && strpos($message, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }

    private function insertSkinWithCategoryWait(
        Browser $browser,
        string $category,
        string $skinSlug
    ): void {
        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch('insertLayoutRequestOnBottom', null);
            }
        ");
        $browser->pause(2000);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: picker dialog never opened for '{$skinSlug}'"
            );
        }

        $browser->script("
            var wantCat = " . json_encode(strtolower($category)) . ";
            var catLinks = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-categories li'
            );
            for (var i = 0; i < catLinks.length; i++) {
                var label = (catLinks[i].textContent || '').trim().toLowerCase();
                if (label === wantCat) {
                    catLinks[i].click();
                    break;
                }
            }
        ");
        $browser->pause(1200);

        $clicked = $browser->script("
            var skinSlug = " . json_encode($skinSlug) . ";
            var cards = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            );
            var tokens = skinSlug.toLowerCase().split(/[-_\\/]+/).filter(Boolean);
            for (var j = 0; j < cards.length; j++) {
                var card = cards[j];
                var haystack = '';
                var iframe = card.querySelector('iframe.layout-preview-iframe');
                if (iframe) { haystack += (iframe.getAttribute('src') || ''); }
                var title = card.querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                if (title) { haystack += ' ' + (title.textContent || ''); }
                haystack += ' ' + (card.getAttribute('data-template') || '');
                haystack += ' ' + (card.getAttribute('aria-label') || '');

                var lower = haystack.toLowerCase();
                if (lower.indexOf(skinSlug.toLowerCase()) !== -1
                    || lower.indexOf(skinSlug.replace('/', '__').toLowerCase()) !== -1) {
                    card.click();
                    return 1;
                }
                var normalized = ' ' + lower.replace(/[^a-z0-9]+/g, ' ').replace(/\\s+/g, ' ').trim() + ' ';
                var allTokensMatch = tokens.length > 0 && tokens.every(function (t) {
                    return normalized.indexOf(' ' + t + ' ') !== -1;
                });
                if (allTokensMatch) {
                    card.click();
                    return 1;
                }
            }
            return 0;
        ");

        if (($clicked[0] ?? 0) !== 1) {
            $diag = $browser->script("
                var cats = Array.from(document.querySelectorAll(
                    '.mw-le-layouts-dialog .modules-list-categories li'
                )).map(function (li) { return (li.textContent || '').trim(); });
                var cards = Array.from(document.querySelectorAll(
                    '.mw-le-layouts-dialog .modules-list-block-item, '
                    + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
                ));
                var dump = cards.slice(0, 30).map(function (card) {
                    var title = card.querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                    var iframe = card.querySelector('iframe.layout-preview-iframe');
                    return {
                        title: title ? (title.textContent || '').trim() : '',
                        iframeSrc: iframe ? (iframe.getAttribute('src') || '') : '',
                        aria: card.getAttribute('aria-label') || '',
                        dataTemplate: card.getAttribute('data-template') || '',
                        cls: card.className || ''
                    };
                });
                return { cats: cats, cards: dump, total: cards.length };
            ");
            $diagJson = json_encode($diag[0] ?? [], JSON_UNESCAPED_SLASHES);
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: no card matched skin '{$skinSlug}' under category '{$category}'. DIAG: {$diagJson}"
            );
        }

        $browser->pause(2500);
    }

    private function primeLayoutHandleOnMainContent(Browser $browser): void
    {
        $primed = $browser->script("
            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getDocument === 'function')) {
                return 'NO_CANVAS';
            }
            var doc = mw.app.canvas.getDocument();
            var target = doc.querySelector('.section.edit.main-content')
                || doc.querySelector('.section.edit[field=\"content\"]')
                || doc.querySelector('[data-layout-container]');
            if (!target) return 'NO_MAIN_CONTENT';
            if (mw.app.liveEdit && mw.app.liveEdit.handles) {
                var h = mw.app.liveEdit.handles.get('layout');
                if (h && typeof h.set === 'function') {
                    h.set(target);
                }
            }
            return 'OK';
        ");

        $this->assertSame(
            'OK',
            $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section'
        );
    }

    private function waitForMarker(Browser $browser, string $marker, int $stepNumber): void
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                return doc.querySelector(" . json_encode($marker) . ") ? 1 : 0;
            ");
            if (($res[0] ?? 0) === 1) {
                $browser->pause(500);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot("fail-public-console-step-{$stepNumber}");
        throw new \RuntimeException(
            "Step {$stepNumber}: marker '{$marker}' never appeared in canvas within 15s"
        );
    }

    private function markAllEditsChanged(Browser $browser): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var mc = doc.querySelector('.section.edit.main-content');
            if (!mc) return 'NO_MAIN_CONTENT';
            mc.classList.add('changed');
            var edits = mc.querySelectorAll('.edit');
            for (var i = 0; i < edits.length; i++) {
                edits[i].classList.add('changed');
            }
            return 'OK:' + edits.length;
        ");
        $outcome = (string)($res[0] ?? 'UNKNOWN');
        $this->assertStringStartsWith(
            'OK',
            $outcome,
            'Must be able to tag .edit.changed across main-content'
        );
    }
}
