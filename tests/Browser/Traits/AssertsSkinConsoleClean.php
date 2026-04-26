<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

/**
 * Plan B.3 fourth-bullet helper — every per-skin Dusk test must
 * assert that no JS console error fires during insert OR public
 * render. Without this gate, a skin can render the right markup,
 * persist the right shortcode, AND throw a top-level reference
 * error at boot — silently breaking the page for real users
 * while every other Plan B.3 contract bullet stays green.
 *
 * The pattern (lifted verbatim from
 * {@see \Tests\Browser\LiveEditPublicPageConsoleCleanTest} and
 * {@see \Tests\Browser\LiveEditColorPalettePublicRenderConsoleCleanTest})
 * uses two independent capture channels so the lifecycle has no
 * blind spots:
 *
 *   1. **In-page error guard** — registers `window.addEventListener
 *      ('error', …)` and `('unhandledrejection', …)`. Catches
 *      anything that fires AFTER the guard is installed: async
 *      bootstrap code, deferred-script throws, promise rejections
 *      from font/asset loaders.
 *   2. **ChromeDriver browser log** — drained via
 *      `driver->manage()->getLog('browser')`, filtered to SEVERE.
 *      Catches anything BEFORE the guard could attach: initial
 *      parse errors, top-level throws from module bundles, failed
 *      resource loads that ChromeDriver promotes to SEVERE.
 *
 * The TODO line names `window.__consoleErrors` as the example
 * surface; the established sibling-tests use the more robust
 * two-channel approach instead, so this trait does too. The same
 * skip list is used so policy stays consistent (only intermittent
 * dev-server chunk-close noise + the install_log.txt 404 that
 * fires on fresh installs are filtered).
 *
 * Usage in a per-skin test:
 *
 *   $this->installInPageErrorGuard($browser);
 *   // ... drive insert / public visit ...
 *   $this->assertNoConsoleErrors($browser, 'after insert + save');
 *
 * For tests that have a clear insert-vs-public boundary, call the
 * assertion twice: once at the end of the canvas/insert phase,
 * once after the public-render visit. Both share the same skip
 * list and noise floor.
 */
trait AssertsSkinConsoleClean
{
    /**
     * Console-noise filter mirrors the established sibling pattern
     * in {@see \Tests\Browser\LiveEditPublicPageConsoleCleanTest::CONSOLE_NOISE_PATTERNS}.
     * Match anywhere in the message text → skip the entry. Keep
     * the list tight: anything new added here weakens the gate.
     *
     * @var array<int, string>
     */
    private const CONSOLE_NOISE_PATTERNS = [
        'install_log.txt',
        'ERR_CONTENT_LENGTH_MISMATCH',
        // Benign Chromium watchdog message that fires when an element
        // resize triggers a layout while ResizeObserver is still
        // dispatching the previous notification cycle. It's a
        // non-issue (the spec explicitly tolerates it) but Chrome
        // promotes it to `error` event level — see
        // https://github.com/WICG/resize-observer/issues/38. Filtering
        // here prevents the in-page guard from flagging it as a
        // regression.
        'ResizeObserver loop completed with undelivered notifications',
        'ResizeObserver loop limit exceeded',
    ];

    /**
     * Install the in-page error / promise-rejection guard. Idempotent
     * (re-installs are no-ops via the `__skinConsoleGuardInstalled`
     * flag), so callers can call it after every navigation without
     * tracking installation state.
     *
     * Call AFTER `$browser->visit(…)` but BEFORE the settle pause —
     * before-visit calls would attach to a soon-to-be-replaced
     * window object, after-settle calls would miss the bootstrap
     * window where most regressions actually fire.
     */
    protected function installInPageErrorGuard(Browser $browser): void
    {
        $browser->script("
            (function () {
                if (window.__skinConsoleGuardInstalled) return;
                window.__skinConsoleGuardInstalled = true;
                window.__skinConsoleGuardEvents = [];

                window.addEventListener('error', function (ev) {
                    try {
                        window.__skinConsoleGuardEvents.push({
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
                        window.__skinConsoleGuardEvents.push({
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
     * Drain the ChromeDriver browser log without inspecting it.
     * Useful between phases (after insert, before public visit) so
     * the next read is scoped to events from the new phase only.
     */
    protected function drainBrowserLog(Browser $browser): void
    {
        try {
            $browser->driver->manage()->getLog('browser');
        } catch (\Throwable) {
            // ChromeDriver log API can be flaky under certain builds.
        }
    }

    /**
     * Combined assertion: read both channels (in-page guard events
     * and SEVERE browser-log entries), filter out noise, and fail
     * if either channel has anything left.
     *
     * `$context` ends up in the failure message so a test that
     * calls this twice (after insert, after public render) can be
     * told apart from one phase to the other when reading CI logs.
     */
    protected function assertNoConsoleErrors(Browser $browser, string $context): void
    {
        $inPageErrors = $this->readInPageErrors($browser);
        $severeLogs = $this->readSevereLogs($browser);

        $this->assertNoConsoleErrorsAgainstChannels($context, $inPageErrors, $severeLogs);
    }

    /**
     * The pure assertion half — separately testable so the Feature
     * contract test can exercise the noise filter + failure-message
     * shape without spinning up a Dusk browser.
     *
     * @param array<int, array{kind:string, message:string, filename:string, line:int|null}> $inPageErrors
     * @param array<int, array{level:string, message:string}> $severeLogs
     */
    protected function assertNoConsoleErrorsAgainstChannels(
        string $context,
        array $inPageErrors,
        array $severeLogs,
    ): void {
        $this->assertSame(
            [],
            $inPageErrors,
            sprintf(
                'Plan B.3 fourth-bullet (%s): no uncaught error or unhandled promise '
                . 'rejection may fire during insert OR public render. In-page guard '
                . 'events: %s',
                $context,
                json_encode($inPageErrors, JSON_UNESCAPED_SLASHES),
            ),
        );

        $this->assertSame(
            [],
            $severeLogs,
            sprintf(
                'Plan B.3 fourth-bullet (%s): no SEVERE browser-log entry may fire '
                . 'during insert OR public render (JS parse errors, top-level throws, '
                . 'failed resource loads that promote to SEVERE). Entries: %s',
                $context,
                json_encode($severeLogs, JSON_UNESCAPED_SLASHES),
            ),
        );
    }

    /**
     * @return array<int, array{kind:string, message:string, filename:string, line:int|null}>
     */
    protected function readInPageErrors(Browser $browser): array
    {
        $res = $browser->script('
            var events = (window.__skinConsoleGuardEvents || []).slice();
            return events;
        ');

        $payload = $res[0] ?? [];
        if (! is_array($payload)) {
            return [];
        }

        $out = [];
        foreach ($payload as $event) {
            if (! is_array($event)) {
                continue;
            }
            $message = (string) ($event['message'] ?? '');
            if ($this->isConsoleNoise($message)) {
                continue;
            }
            $out[] = [
                'kind' => (string) ($event['kind'] ?? ''),
                'message' => $message,
                'filename' => (string) ($event['filename'] ?? ''),
                'line' => is_int($event['line'] ?? null) ? (int) $event['line'] : null,
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{level:string, message:string}>
     */
    protected function readSevereLogs(Browser $browser): array
    {
        try {
            $logs = $browser->driver->manage()->getLog('browser');
        } catch (\Throwable) {
            return [];
        }

        $out = [];
        foreach ($logs as $log) {
            $level = (string) ($log['level'] ?? '');
            $message = (string) ($log['message'] ?? '');
            if ($level !== 'SEVERE') {
                continue;
            }
            if ($this->isConsoleNoise($message)) {
                continue;
            }
            $out[] = ['level' => $level, 'message' => $message];
        }

        return $out;
    }

    /**
     * True when the message text matches one of the
     * intentionally-ignored noise patterns.
     */
    protected function isConsoleNoise(string $message): bool
    {
        foreach (self::CONSOLE_NOISE_PATTERNS as $pattern) {
            if ($pattern !== '' && strpos($message, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
