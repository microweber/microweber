<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AssertsSkinPublicSignatureRendered;
use Tests\TestCase;

/**
 * Plan B.3 third-bullet contract — pin the
 * {@see AssertsSkinPublicSignatureRendered} trait's source-matching
 * behaviour so the per-skin Dusk tests inherit a verified gate.
 *
 * Three contract slices:
 *
 *   1. All markers found → assertion passes silently. The trait
 *      doesn't return early on the first match; it must validate
 *      every marker in the array, so a regression that drops one
 *      while preserving another is still caught.
 *   2. One marker missing → fails with a message naming both the
 *      page slug and the missing marker (so the operator can grep
 *      for it without reading a stack trace).
 *   3. Empty marker array → fails fast with a "you passed nothing"
 *      message, NOT a silent pass. A test that calls the trait
 *      with no markers should be louder than one that calls it
 *      with one wrong marker.
 *
 * The Feature class is the natural home for these tests because
 * the trait is consumed by Dusk tests (`Tests\TestCase` is the
 * shared base) and exercising the source-matching half doesn't
 * require a real browser — it tests the
 * `assertSkinSignatureMarkersFoundInSource` half of the trait
 * with synthetic source strings.
 */
class AssertsSkinPublicSignatureRenderedTraitTest extends TestCase
{
    use AssertsSkinPublicSignatureRendered;

    private const FAKE_SLUG = 'landing-test-fake';

    private const FAKE_SOURCE = <<<'HTML'
        <!doctype html>
        <html>
        <body>
          <section class="section pricing-skin-2 edit safe-mode" field="layout-pricing-skin-2-42">
            <div class="container mw-layout-container">…</div>
          </section>
        </body>
        </html>
        HTML;

    #[Test]
    public function every_marker_found_passes_quietly(): void
    {
        $this->assertSkinSignatureMarkersFoundInSource(
            self::FAKE_SLUG,
            self::FAKE_SOURCE,
            ['field="layout-pricing-skin-2-', 'pricing-skin-2'],
        );
    }

    #[Test]
    public function missing_marker_fails_with_slug_and_marker_in_message(): void
    {
        $missingMarker = 'pricing-skin-9999-not-here';

        try {
            $this->assertSkinSignatureMarkersFoundInSource(
                self::FAKE_SLUG,
                self::FAKE_SOURCE,
                ['field="layout-pricing-skin-2-', $missingMarker],
            );
            $this->fail('Expected AssertsSkinPublicSignatureRendered to throw on a missing marker');
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString(
                $missingMarker,
                $e->getMessage(),
                'Failure message must name the missing marker so the operator can grep for it'
            );
            $this->assertStringContainsString(
                self::FAKE_SLUG,
                $e->getMessage(),
                'Failure message must name the page slug so the operator knows which page to inspect'
            );
        }
    }

    #[Test]
    public function empty_marker_array_fails_fast_rather_than_silently_passing(): void
    {
        try {
            $this->assertSkinSignatureMarkersFoundInSource(
                self::FAKE_SLUG,
                self::FAKE_SOURCE,
                [],
            );
            $this->fail('Expected AssertsSkinPublicSignatureRendered to refuse an empty marker array');
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString(
                'at least one signature marker is required',
                $e->getMessage(),
                'Empty-marker failure must explicitly call out that the caller passed nothing'
            );
        }
    }
}
