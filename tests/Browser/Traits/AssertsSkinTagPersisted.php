<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Plan B.3 second-bullet helper — every per-skin Dusk test must
 * assert that inserting the skin on an empty live-edit canvas
 * persists a `<module type="layouts" template="<family>/<skin>">`
 * shortcode somewhere reachable from the page's content row.
 *
 * Why a trait, not an inline check per test: the persistence
 * surface is the same for every skin (the picker writes the
 * shortcode to `content.content` / `content.content_body`, with
 * spillover into `content_fields` for satellite module data), and
 * the haystack-build incantation is identical. Centralising it in
 * one place gives:
 *
 *   - One stop where the contract is documented and verifiable.
 *   - One place to update if the save pipeline ever changes which
 *     column the shortcode lands in.
 *   - A single failure message that names both the page id and
 *     the skin tag, so a regression that drops the persistence is
 *     actionable without grepping.
 *
 * Composes alongside {@see AssertsSkinBladeExists} on every
 * per-skin test. Like that trait, this one is normally consumed
 * from a Dusk test class but only needs the application container
 * (for {@see DB} access) — the {@see AssertsSkinTagPersistedTraitTest}
 * Feature test runs against a real DB without a browser.
 *
 * Usage in a per-skin test:
 *
 *   $this->saveLiveEdit($browser);
 *   $this->assertSkinTagPersisted($landing->pageId, 'pricing/skin-1');
 */
trait AssertsSkinTagPersisted
{
    /**
     * Assert the `template="<skinTag>"` shortcode landed somewhere
     * reachable from the page's content row after save.
     *
     * Build a haystack across every column the live-edit save
     * pipeline can spill into:
     *   - `content.content` (Phase-2 default landing target)
     *   - `content.content_body` (some skins write here instead;
     *     varies by content_type and template)
     *   - `content_fields` rows where rel_type=module (satellite
     *     module attributes — `<module type="layouts">` shortcodes
     *     leak here when the picker drops a layout containing
     *     nested modules)
     *   - `content_fields` rows where rel_type=content + rel_id
     *     points at this page (the page's own non-content columns
     *     occasionally carry the shortcode when the section was
     *     marked `.changed` at save time)
     *
     * The haystack-union approach is necessary because the save
     * pipeline's column choice is not deterministic per-skin — it
     * depends on the insertion target and the surrounding section's
     * `field=…` attribute. Searching only `content.content` would
     * miss valid persists for ecommerce/blog skins that write to
     * `content_body`.
     */
    protected function assertSkinTagPersisted(int $pageId, string $skinTag): void
    {
        $content = DB::table('content')->where('id', $pageId)->first();
        $this->assertNotNull(
            $content,
            "content row for page {$pageId} must still exist after save (Plan B.3 second-bullet contract)"
        );

        $moduleFields = DB::table('content_fields')
            ->where('rel_type', 'module')
            ->pluck('value')
            ->all();
        $pageFields = DB::table('content_fields')
            ->where('rel_type', 'content')
            ->where('rel_id', $pageId)
            ->pluck('value')
            ->all();

        $haystack = implode("\n", array_filter([
            (string) ($content->content ?? ''),
            (string) ($content->content_body ?? ''),
            implode("\n", array_map(fn ($v) => (string) $v, $moduleFields)),
            implode("\n", array_map(fn ($v) => (string) $v, $pageFields)),
        ]));

        $this->assertNotSame(
            '',
            $haystack,
            "Plan B.3 second-bullet: page {$pageId} must have at least one "
            . 'non-empty content / content_body / content_fields row after save '
            . "(found nothing — did the save XHR actually land?)"
        );

        $this->assertStringContainsString(
            $skinTag,
            $haystack,
            sprintf(
                'Plan B.3 second-bullet: inserting %s on an empty live-edit '
                . 'canvas must persist a `<module type="layouts" template="%s">` '
                . 'shortcode somewhere reachable from page %d (content / '
                . 'content_body / content_fields). The skin tag was not found '
                . 'in any of those columns — the picker insertion did not '
                . 'survive the save round-trip.',
                $skinTag,
                $skinTag,
                $pageId,
            ),
        );
    }
}
