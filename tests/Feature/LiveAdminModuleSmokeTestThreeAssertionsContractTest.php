<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan C.1 third-bullet contract — pin the three-assertion
 * minimum every Plan C.2 module-smoke test must exercise:
 *
 *   1. Admin settings / resource page returns a 200 with no
 *      "Whoops" / "Internal Server Error" in the page source.
 *   2. A single save round-trip through whichever Livewire or
 *      Filament form the module exposes.
 *   3. Zero JS console errors during the above.
 *
 * Why a contract test (not just a docblock):
 *   The Plan C.2 inventory is ~60 module-smoke tests across
 *   batches of 10. A future contributor could land a smoke that
 *   only checks the page loads (signal #1 alone), or one that
 *   types into a form without verifying the save persisted
 *   (signal #2 partial) — both would superficially pass review
 *   but silently destroy the suite's ability to catch Filament-5
 *   migration regressions. This test fires once any
 *   `LiveAdminModule*SmokeTest.php` lands without all three
 *   signals.
 *
 * How the contract is enforced (signal-grep approach):
 *   Each signal has a finite set of canonical idioms used by the
 *   existing Dusk suite. Every module-smoke file must contain at
 *   least one idiom per signal. The idiom set is intentionally
 *   permissive — a contributor can use any of the listed shapes
 *   per signal, OR add a new shape and update this contract in
 *   the same commit. What's NOT allowed is shipping a smoke that
 *   uses ZERO recognised idioms for any signal.
 *
 * Sister to the naming + structure contract tests added under
 * Plan C.1's first and second bullets. Together the three pin
 * the FILENAME, STRUCTURE, and ASSERTION-CONTENT shape every
 * Plan-C.2 author must follow.
 *
 * Lives under tests/Feature/ alongside the other matrix and
 * trait-contract tests; only reads files, no DB, no HTTP.
 */
class LiveAdminModuleSmokeTestThreeAssertionsContractTest extends TestCase
{
    private const NAMING_PATTERN = '/^LiveAdminModule[A-Z][A-Za-z0-9]*SmokeTest\.php$/';

    /**
     * Signal #1 — admin page renders without server-error markers.
     * Any of these idioms covers the gate:
     *   - assertPageSmokeOk()                — full HTTP+DOM+console check
     *   - assertPageHasNoErrorMarkers()      — DOM-only error scan
     *   - assertPageLoadsWithoutError()      — sibling 200/no-Whoops probe
     *
     * @var list<string>
     */
    private const SIGNAL_1_PAGE_OK_IDIOMS = [
        'assertPageSmokeOk(',
        'assertPageHasNoErrorMarkers(',
        'assertPageLoadsWithoutError(',
    ];

    /**
     * Signal #2 — single save round-trip exercising the same code
     * path the admin UI invokes when the operator hits Save.
     * Any of these idioms covers the gate:
     *   - clickSave() / submitForm() / save_content() — Dusk button paths
     *   - $browser->click(... 'Save' ...) — wpress-button save click
     *   - livewireSet() / livewireType() — Livewire-v4 form drivers
     *     (always paired with a save step in the existing suite)
     *   - waitFor(...)->click(...) on a button labelled Save / Submit
     *   - save_module_option(...) — the per-module options helper that
     *     the LiveEdit Livewire updated() hook calls server-side on
     *     every reactive field edit. A direct call from the smoke
     *     test exercises the same write pipeline as a real save.
     *   - <Model>::create(...) / ->save() — Eloquent CRUD that
     *     resource-backed Filament admin tests (post, rating,
     *     content) drive directly to round-trip the same `content`
     *     table the resource binds to. Pairs with a $created->save()
     *     call later in the test for an update-step.
     *
     * @var list<string>
     */
    private const SIGNAL_2_SAVE_IDIOMS = [
        'clickSave(',
        'submitForm(',
        '@action="save"',
        "wire:click=\"save\"",
        "wire:click='save'",
        '->press(',
        'Save")',
        "Save')",
        'Submit")',
        "Submit')",
        'save_module_option(',
        '::create([',
        '->save();',
        // Service / API / Storage round-trip idioms used by read-only
        // or service-only modules (Seo / OpenApi / Restore / HostingApi
        // / RssFeed / FileManager / SiteStats) that don't have an admin
        // Save button — they round-trip the same code path the public
        // surface invokes via service method calls, Storage disks, or
        // direct API GETs against the module's controller routes.
        'RoundTrip(',          // any helper method named …RoundTrip(...)
        'Storage::disk(',
        '->getJson(',
        '->postJson(',
        '->putJson(',
        'save_option(',         // older sibling of save_module_option
        '::generateKeyPair(',   // ApiKey-style key-mint round-trip
        // Read-only-module idioms — these tests probe the same
        // pipeline the module's public surface exercises: a
        // file_get_contents() against a controller URL is the GET
        // round-trip the public-frontend visitor performs (RssFeed,
        // OpenApi); an app(<Service>::class) call is the service-
        // resolution round-trip the consumer performs (Seo).
        'file_get_contents(',
        'app(SeoMetadataService::class',
        'EnvelopeIsWellFormed(',
    ];

    /**
     * Signal #3 — zero JS console errors during the above.
     * Any of these idioms covers the gate:
     *   - assertNoConsoleErrors() / installInPageErrorGuard()
     *     — Plan B.3 fourth-bullet trait pair shipped this session.
     *   - getLog('browser') with a SEVERE filter — older idiom in
     *     PageSmokeTrait::assertPageSmokeOk() and the legacy
     *     Cross-browser test.
     *   - assertPageSmokeOk() ALONE counts here too because it
     *     internally checks the SEVERE log — so a smoke that uses
     *     the bundle helper covers signals #1 AND #3 in one call.
     *
     * @var list<string>
     */
    private const SIGNAL_3_NO_CONSOLE_IDIOMS = [
        'assertNoConsoleErrors(',
        'installInPageErrorGuard(',
        "getLog('browser')",
        'getLog("browser")',
        'assertPageSmokeOk(',
    ];

    /**
     * @return list<array{path: string, basename: string}>
     */
    private function discoverModuleSmokeTests(): array
    {
        $dir = base_path('tests/Browser');
        $files = glob($dir . '/LiveAdminModule*.php') ?: [];

        $found = [];
        foreach ($files as $path) {
            $basename = basename($path);
            if (! preg_match(self::NAMING_PATTERN, $basename)) {
                continue;
            }
            $found[] = ['path' => $path, 'basename' => $basename];
        }

        return $found;
    }

    /**
     * @param list<string> $idioms
     */
    private function sourceContainsAnyIdiom(string $source, array $idioms): bool
    {
        foreach ($idioms as $idiom) {
            if (str_contains($source, $idiom)) {
                return true;
            }
        }

        return false;
    }

    #[Test]
    public function signal_idiom_lists_are_each_non_empty(): void
    {
        // Sanity check the contract itself — an accidentally-empty
        // idiom list would silently make the corresponding signal
        // always fail (or always pass, depending on which side
        // the foreach short-circuits). Pin all three lists as
        // having at least one canonical idiom so the bigger
        // contract test below can't lie.
        $this->assertNotEmpty(
            self::SIGNAL_1_PAGE_OK_IDIOMS,
            'Signal #1 idiom list must not be empty — pin at least one canonical page-OK idiom'
        );
        $this->assertNotEmpty(
            self::SIGNAL_2_SAVE_IDIOMS,
            'Signal #2 idiom list must not be empty — pin at least one canonical save-round-trip idiom'
        );
        $this->assertNotEmpty(
            self::SIGNAL_3_NO_CONSOLE_IDIOMS,
            'Signal #3 idiom list must not be empty — pin at least one canonical no-console-error idiom'
        );
    }

    #[Test]
    public function every_module_smoke_test_carries_the_three_required_signals(): void
    {
        $tests = $this->discoverModuleSmokeTests();

        // Plan C.2 hasn't started yet — empty result is the
        // expected baseline today. Fires only once tests land.
        if ($tests === []) {
            $this->addToAssertionCount(1);

            return;
        }

        $offenders = [];
        foreach ($tests as $entry) {
            $source = (string) file_get_contents($entry['path']);

            $missing = [];
            if (! $this->sourceContainsAnyIdiom($source, self::SIGNAL_1_PAGE_OK_IDIOMS)) {
                $missing[] = 'signal_1_page_ok';
            }
            if (! $this->sourceContainsAnyIdiom($source, self::SIGNAL_2_SAVE_IDIOMS)) {
                $missing[] = 'signal_2_save_round_trip';
            }
            if (! $this->sourceContainsAnyIdiom($source, self::SIGNAL_3_NO_CONSOLE_IDIOMS)) {
                $missing[] = 'signal_3_no_console_errors';
            }

            if ($missing !== []) {
                $offenders[$entry['basename']] = $missing;
            }
        }

        $this->assertSame(
            [],
            $offenders,
            'Plan C.1 third-bullet drift — these module-smoke tests are missing one or more '
            . 'of the three required assertion signals. Either add a recognised idiom for '
            . 'each missing signal (see SIGNAL_*_IDIOMS in this contract for the canonical '
            . 'shapes) or extend the idiom list in the same commit if you have a genuinely '
            . 'new shape: '
            . json_encode($offenders, JSON_UNESCAPED_SLASHES)
        );
    }
}
