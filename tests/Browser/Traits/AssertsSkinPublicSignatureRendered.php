<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

/**
 * Plan B.3 third-bullet helper — every per-skin Dusk test must
 * assert that the public render of the page carries the skin's
 * signature markup (a family-specific class or, for layout skins,
 * the `field="layout-<family>-<skin>-…"` attribute the blade
 * emits on the outer `<section>`).
 *
 * This is the trait sibling of {@see AssertsSkinBladeExists} and
 * {@see AssertsSkinTagPersisted}. Centralising the signature-class
 * check in one method gives:
 *
 *   - One consistent failure message naming both the page slug and
 *     the missing marker, so a regression that drops the skin from
 *     the public render is actionable from the failure alone.
 *   - One place to update if the public-page render shell ever
 *     evolves (e.g. wraps every skin in an additional shadow root).
 *   - A trait-level Feature test pinning the contract against a
 *     synthetic source string, so the Dusk per-skin tests inherit
 *     a verified gate, not a "looks right" one.
 *
 * Marker design: per-skin signature classes are NOT derived from
 * the skin tag. Some skins have unique outer-section classes
 * (`features-skin-2-advantages`, `pricing-skin-3`, `mw-menu-skin-com`),
 * others have only the `field="layout-…"` attribute, and a few
 * (ecommerce/skin-1) have no skin-unique outer marker at all and
 * must rely on inner shortcode children. Derivation would be
 * fragile against this variance, so the trait takes an explicit
 * `$signatureMarkers` array — each per-skin test passes the
 * substrings that uniquely identify its skin in the public source.
 *
 * Usage in a per-skin test:
 *
 *   $this->assertSkinPublicSignatureRendered(
 *       $browser,
 *       $landing->slug,
 *       ['field="layout-jumbotron-skin-1-', 'mw-layout-dark-background'],
 *   );
 */
trait AssertsSkinPublicSignatureRendered
{
    /**
     * Visit the public page URL and assert the rendered HTML
     * source contains every supplied signature marker substring.
     *
     * @param string[] $signatureMarkers Substrings that must appear
     *                                    in the rendered source.
     */
    protected function assertSkinPublicSignatureRendered(
        Browser $browser,
        string $slug,
        array $signatureMarkers,
        int $pauseMs = 2500,
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause($pauseMs);
        $source = (string) $browser->driver->getPageSource();

        $this->assertSkinSignatureMarkersFoundInSource($slug, $source, $signatureMarkers);
    }

    /**
     * The pure assertion half of {@see assertSkinPublicSignatureRendered}
     * — split out so the Feature contract test can exercise the
     * marker-matching logic without spinning up a Dusk browser.
     *
     * @param string[] $signatureMarkers
     */
    protected function assertSkinSignatureMarkersFoundInSource(
        string $slug,
        string $source,
        array $signatureMarkers,
    ): void {
        $this->assertNotEmpty(
            $signatureMarkers,
            'assertSkinSignatureMarkersFoundInSource: at least one signature marker is required'
            . ' (passing an empty array would silently always pass — Plan B.3 third-bullet'
            . ' contract requires the assertion to mean something)'
        );

        foreach ($signatureMarkers as $marker) {
            $this->assertStringContainsString(
                $marker,
                $source,
                sprintf(
                    'Plan B.3 third-bullet: public /%s must render the skin signature '
                    . "marker '%s'. The marker was not in the page source — the skin "
                    . 'either did not render at all, or its outer markup changed in a '
                    . 'way the per-skin Dusk test no longer recognises.',
                    $slug,
                    $marker,
                ),
            );
        }
    }
}
