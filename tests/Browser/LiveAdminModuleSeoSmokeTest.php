<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Modules\Seo\Services\SeoMetadataService;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Seo module smoke (SEO settings + storefront meta).
 *
 * Service-class shape: the Seo module has no Filament settings
 * page of its own (see Modules/Seo/Providers/SeoServiceProvider.php
 * — register() binds the SeoMetadataService singleton, registers
 * @seoMetaTags / @seoTitle / @seoDescription Blade directives,
 * and loads migrations, but does not call
 * FilamentRegistry::registerPage). Operator-visible SEO settings
 * flow through:
 *
 *   - The website_title / website_description / website_keywords
 *     options the SeoMetadataService falls back to on the home /
 *     archive surfaces (see ::getSiteTitle / ::getSiteDescription
 *     / ::getSiteKeywords in SeoMetadataService.php), and
 *   - The @seoMetaTags Blade directive admin templates call to
 *     emit the meta tag block on every public page.
 *
 * The smoke covers both halves:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of the public storefront `/`
 *      — the canonical surface where every SEO meta tag the
 *      module emits is operator-visible. A regression that
 *      breaks the @seoMetaTags directive surfaces here as
 *      either an HTTP error or a console-visible JS exception
 *      (some 3rd-party SEO plugins read meta tags via JS at
 *      DOMReady).
 *   2. Signal #2 (SeoMetadataService round-trip): resolves the
 *      service singleton from the container and asserts
 *      ::getDefaultMetadata returns a fully-shaped envelope —
 *      title, description, keywords, canonical_url, robots,
 *      og, twitter — each of which the @seoMetaTags directive
 *      pulls from on every public-page render. A regression in
 *      the Option::getValue fallback chain (which is what every
 *      "site title" / "site description" admin form ultimately
 *      writes to) would silently break the operator-configured
 *      SEO defaults across every page on the storefront.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      storefront page after settle, with a 1.5s window
 *      catching any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait — kept
 * aligned with the Plan-C.2 sibling shape so any future auth-
 * gating change is detected automatically).
 *
 * Read-only — no fixture rows to clean up. Safe to re-run.
 */
class LiveAdminModuleSeoSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const STOREFRONT_PATH = '/';

    private const REQUIRED_METADATA_KEYS = [
        'title',
        'description',
        'keywords',
        'canonical_url',
        'robots',
        'og',
        'twitter',
    ];

    private const REQUIRED_OG_KEYS = [
        'title',
        'description',
        'type',
        'url',
        'site_name',
    ];

    private const REQUIRED_TWITTER_KEYS = [
        'card',
        'title',
        'description',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server.
    }

    #[Test]
    public function seo_storefront_renders_meta_and_service_round_trips_default_envelope(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the public
            // storefront `/` (the canonical surface where every
            // SEO meta tag the module emits is operator-visible).
            $this->assertPageSmokeOk(
                $browser,
                self::STOREFRONT_PATH,
                'storefront SEO meta surface',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'storefront SEO meta surface render');

            // Confirm the storefront emitted the bare-minimum
            // SEO chrome — at least a <title> element. A
            // regression that strips the @seoTitle directive
            // would silently render an anonymous tab in every
            // browser tab.
            $this->assertStorefrontSeoChromeRendered($browser);
        });

        // Signal #2 — SeoMetadataService default-envelope round-
        // trip. Same pipeline @seoMetaTags pulls from on every
        // public-page render (see the Blade directive in
        // SeoServiceProvider::registerBladeDirectives).
        $this->assertSeoMetadataServiceDefaultsAreShaped();
    }

    /**
     * Resolve the SeoMetadataService singleton and assert
     * ::getDefaultMetadata returns the fully-shaped envelope
     * the @seoMetaTags directive pulls from. A regression in
     * any of the Option::getValue fallbacks the service reads
     * would surface here as a missing key or a non-array
     * sub-envelope.
     */
    private function assertSeoMetadataServiceDefaultsAreShaped(): void
    {
        $service = app(SeoMetadataService::class);

        $this->assertInstanceOf(
            SeoMetadataService::class,
            $service,
            'The Seo module must bind SeoMetadataService as a container singleton — '
            . 'see SeoServiceProvider::register(). The @seoMetaTags / @seoTitle / '
            . '@seoDescription Blade directives all resolve the service through '
            . 'app(...). A regression in the binding would break every public-page '
            . 'meta-tag render.'
        );

        $defaults = $service->getDefaultMetadata();

        $this->assertIsArray(
            $defaults,
            'SeoMetadataService::getDefaultMetadata must return an array — the '
            . 'service\'s consumers (the @seoMetaTags directive, the Open Graph '
            . 'fallback chain in getMetadata(), the sitemap generator) all index '
            . 'into the result. A regression that returns null / an object would '
            . 'silently break every page that has no content-bound metadata.'
        );

        foreach (self::REQUIRED_METADATA_KEYS as $key) {
            $this->assertArrayHasKey(
                $key,
                $defaults,
                'SeoMetadataService::getDefaultMetadata must return a `' . $key
                . '` key — the @seoMetaTags directive emits one <meta> tag per '
                . 'key on every public-page render. A missing key here would '
                . 'silently drop that meta tag from every page on the storefront.'
            );
        }

        $this->assertIsArray(
            $defaults['og'] ?? null,
            'SeoMetadataService::getDefaultMetadata `og` sub-envelope must be an '
            . 'array — every OG meta tag (og:title / og:description / og:type / '
            . 'og:url / og:site_name) is rendered from this sub-envelope by the '
            . '@seoMetaTags directive. A regression here would silently strip '
            . 'every OG tag and break Facebook / LinkedIn link previews.'
        );
        foreach (self::REQUIRED_OG_KEYS as $ogKey) {
            $this->assertArrayHasKey(
                $ogKey,
                $defaults['og'],
                'SeoMetadataService default OG envelope must include `' . $ogKey
                . '` — Facebook / LinkedIn / Slack link-preview readers all '
                . 'rely on this exact OG tag set. A missing key here would '
                . 'silently break the storefront\'s social-share previews.'
            );
        }

        $this->assertIsArray(
            $defaults['twitter'] ?? null,
            'SeoMetadataService::getDefaultMetadata `twitter` sub-envelope must '
            . 'be an array — every Twitter Card meta tag (twitter:card / '
            . 'twitter:title / twitter:description) is rendered from this sub-'
            . 'envelope. A regression here would silently break X / Twitter '
            . 'link previews.'
        );
        foreach (self::REQUIRED_TWITTER_KEYS as $twKey) {
            $this->assertArrayHasKey(
                $twKey,
                $defaults['twitter'],
                'SeoMetadataService default Twitter envelope must include `'
                . $twKey . '` — X / Twitter card readers depend on this exact '
                . 'tag set to render the storefront\'s share preview.'
            );
        }
    }

    /**
     * Probe the rendered storefront for the bare-minimum SEO
     * chrome: a <title> element. A regression that strips the
     * @seoTitle Blade directive (or breaks the underlying
     * SeoMetadataService::getSiteTitle Option fallback) would
     * silently render an anonymous tab in every browser tab.
     */
    private function assertStorefrontSeoChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasTitleTag = (bool) preg_match('/<title[^>]*>.+?<\/title>/i', $source);

        $this->assertTrue(
            $hasTitleTag,
            'storefront `/` must render a non-empty <title> element — every browser '
            . 'tab and every search-engine result list reads this string. A regression '
            . 'that strips it (broken @seoTitle directive, broken Option fallback '
            . 'in SeoMetadataService::getSiteTitle) would silently render every page '
            . 'as anonymous in every tab and every SERP.'
        );
    }
}
