<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-70 / AI-68 / TICKET-OO — module-picker lazy iframe regression
 * coverage.
 *
 * Pins:
 *   - ListLayouts.vue gates each `<iframe>` on a per-card `inView`
 *     reactive flag (`v-if="iframeIsInView(...)"`) so 17 cards no
 *     longer mount 17 iframes simultaneously.
 *   - An IntersectionObserver is wired up that flips the flag when a
 *     wrapper enters the viewport AND unobserves it once visible
 *     (preserving the "once seen, stays mounted" UX).
 *   - The observer re-attaches on filter changes (filterLayouts) AND
 *     view-mode toggles (switchLayoutsListTypePreview) so newly
 *     rendered wrappers get observed.
 *   - The observer is disconnected in beforeUnmount() so nothing
 *     leaks across modal show/hide cycles.
 *   - The browser-level `loading="lazy"` attribute remains as a
 *     belt-and-braces fallback even though it's not the primary
 *     guarantee any more.
 *
 * Style after the cycle-52..69 contract tests (file-system reads only,
 * no Vue mount). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ModulePickerLazyIframeContractTest extends TestCase
{
    private string $vueSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vueSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Layouts/ListLayouts.vue'
        ));
    }

    #[Test]
    public function iframes_are_gated_by_in_view_flag(): void
    {
        // Both the masonry and list templates must wrap each <iframe>
        // in a v-if that reads the per-card reactive flag — without
        // this, the iframe DOM node exists for every card and the
        // browser starts the network request even with loading=lazy
        // when the card is in flow + above the fold.
        $this->assertMatchesRegularExpression(
            '/<iframe[^>]*\\bv-if="iframeIsInView\\(/s',
            $this->vueSrc,
            'ListLayouts.vue: every <iframe> must be gated by v-if="iframeIsInView(...)"'
        );

        // Pin both card layouts (masonry + list) carry the gate. The
        // shared key generator iframeKeyFor(item, index) is the same
        // for both so a single regex-count check is sufficient.
        $matches = [];
        preg_match_all(
            '/<iframe[^>]*v-if="iframeIsInView/s',
            $this->vueSrc,
            $matches
        );
        $this->assertGreaterThanOrEqual(
            2,
            count($matches[0] ?? []),
            'ListLayouts.vue: BOTH the masonry and list <iframe> blocks must carry the lazy gate (expected >=2 matches, got '
                . count($matches[0] ?? []) . ')'
        );
    }

    #[Test]
    public function placeholder_renders_until_iframe_mounts(): void
    {
        // The user-visible card must keep rendering the title even
        // before the iframe mounts — otherwise empty cards look broken.
        $this->assertStringContainsString(
            'class="layout-iframe-placeholder"',
            $this->vueSrc,
            'ListLayouts.vue: must render a .layout-iframe-placeholder until the iframe mounts'
        );
        // The placeholder must show item.title (template handles
        // item.title for the parent-block already; the placeholder
        // mirrors that for the inner block).
        $this->assertMatchesRegularExpression(
            '/layout-iframe-placeholder[^>]*>\\s*<span>\\{\\{\\s*item\\.title\\s*\\}\\}/s',
            $this->vueSrc,
            'ListLayouts.vue: placeholder must show {{ item.title }} so cards never look empty during scroll'
        );
    }

    #[Test]
    public function intersection_observer_is_wired_up(): void
    {
        // The observer instance, the helper that constructs it, and
        // the disconnect in beforeUnmount must all be present.
        $this->assertStringContainsString(
            'iframeObserver: null',
            $this->vueSrc,
            'ListLayouts.vue: data() must declare iframeObserver: null sentinel'
        );
        $this->assertStringContainsString(
            'iframesInView: {}',
            $this->vueSrc,
            'ListLayouts.vue: data() must declare iframesInView: {} reactive map'
        );
        $this->assertStringContainsString(
            'new IntersectionObserver(',
            $this->vueSrc,
            'ListLayouts.vue: setupIframeObserver must construct an IntersectionObserver'
        );
        // Once an iframe is in view, unobserve it — otherwise the
        // observer fires repeatedly on scroll and burns CPU.
        $this->assertStringContainsString(
            'self.iframeObserver.unobserve(entry.target)',
            $this->vueSrc,
            'ListLayouts.vue: once an iframe enters view, the observer must unobserve it'
        );
        // beforeUnmount must disconnect to prevent leaks across modal
        // show/hide cycles.
        $this->assertMatchesRegularExpression(
            '/beforeUnmount\\(\\)\\s*\\{[^}]*this\\.iframeObserver\\.disconnect\\(\\)/s',
            $this->vueSrc,
            'ListLayouts.vue: beforeUnmount() must call iframeObserver.disconnect()'
        );
    }

    #[Test]
    public function observer_reattaches_on_filter_and_viewmode_change(): void
    {
        // After filterLayouts mutates the rendered list, the previous
        // observer is stale (refs point to nodes that no longer exist
        // after Vue diff). Pin that we re-run setupIframeObserver in
        // the next render tick.
        $this->assertMatchesRegularExpression(
            '/this\\.layoutsListFiltered\\s*=\\s*layoutsFiltered;\\s*\\n[^}]*this\\.\\$nextTick\\([^}]*setupIframeObserver\\(\\)/s',
            $this->vueSrc,
            'ListLayouts.vue: filterLayouts() must $nextTick(setupIframeObserver) after assigning layoutsListFiltered'
        );
        // switchLayoutsListTypePreview swaps the rendered sub-tree
        // (masonry → list → full); same re-attach requirement.
        $this->assertMatchesRegularExpression(
            '/switchLayoutsListTypePreview\\(type\\)\\s*\\{[^}]*this\\.\\$nextTick\\([^}]*setupIframeObserver\\(\\)/s',
            $this->vueSrc,
            'ListLayouts.vue: switchLayoutsListTypePreview must $nextTick(setupIframeObserver) too'
        );
    }

    #[Test]
    public function loading_lazy_remains_as_belt_and_braces(): void
    {
        // Browser-native loading="lazy" doesn't replace the v-if gate
        // (cards still in flow start fetching), but it remains as a
        // safety net for browsers that respect it AFTER the iframe
        // mounts. Pin that the attribute survived the cycle-70 changes.
        $this->assertMatchesRegularExpression(
            '/<iframe[^>]*loading="lazy"/s',
            $this->vueSrc,
            'ListLayouts.vue: <iframe> must keep loading="lazy" as belt-and-braces'
        );
        $this->assertStringContainsString(
            'sandbox="allow-same-origin allow-scripts"',
            $this->vueSrc,
            'ListLayouts.vue: <iframe> sandbox attribute must remain (security invariant)'
        );
    }

    #[Test]
    public function iframe_key_generator_is_stable_per_item(): void
    {
        // The reactive map is keyed by iframeKeyFor(item, index). Pin
        // that the helper prefers item.id over layout_file over
        // preview_url over the array index — so re-filtering doesn't
        // re-mount iframes the user has already seen.
        // Pin both the helper signature and the priority order via
        // direct substring matches — the function body has nested
        // braces so a regex `[^}]*` stops too early.
        $this->assertStringContainsString(
            'iframeKeyFor(item, index)',
            $this->vueSrc,
            'ListLayouts.vue: iframeKeyFor(item, index) helper must exist'
        );
        $this->assertStringContainsString(
            "item.id ?? item.layout_file ?? item.preview_url ?? index",
            $this->vueSrc,
            'ListLayouts.vue: iframeKeyFor must prefer item.id over layout_file over preview_url over index'
        );
    }
}
