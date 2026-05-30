<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-pchip01 — PageChip mobile P1 fix (Path A: Teleport
 * on mobile). Supersedes the SOUL #108 "no Teleport" contract for the
 * mobile viewport ONLY; desktop continues to mount the popover in
 * place because Teleport carries :disabled="!isMobile".
 *
 * Diagnostic the fix addresses:
 *   live-edit-mobile.css line 777 hides
 *   .toolbar-col-container:has(.mw-page-chip-wrapper) at
 *   max-width 768px to free toolbar room. PageChip was nested
 *   inside that hidden ancestor, so the MainDrawer "Pages"
 *   bridge (mwOpenPageChip CustomEvent) opened a popover that
 *   was display:none-clipped to 0x0 — Tier-3 measurement
 *   confirmed pre-fix width 0 height 0.
 *
 * Fix (3 layers):
 *   1. Wrap the popover in <Teleport to="body" :disabled="!isMobile">
 *      so the mobile mount escapes the hidden ancestor.
 *   2. New .mw-page-chip-popover--mobile class binding drives a
 *      position:fixed full-viewport overlay (top:56px / inset 8px /
 *      z-index 100000) inside live-edit-mobile.css's existing
 *      @media (max-width: 768px), (pointer: coarse) block.
 *   3. matchMedia reactivity tracks viewport crossing the 768px
 *      breakpoint (rotation / resize). Listener cleanup is symmetric
 *      across both beforeUnmount and beforeDestroy.
 *
 * Auxiliary changes:
 *   - computeAnchor() short-circuits on mobile (chipRect-flip is
 *     irrelevant when the overlay is position:fixed).
 *   - onOutsideClick() also treats $refs.popover.contains() as
 *     inside, otherwise the teleported popover would dismiss
 *     itself on every click (because root.contains() is false
 *     once teleported).
 *
 * Tier-3 forced-reload Playwright probe at 390x844 confirmed:
 *   - popover.parentNode === document.body (teleported)
 *   - getBoundingClientRect: 374x780 (above the >50 / >100 floor)
 *   - top: 56, left: 8 (matches mobile-overlay CSS)
 *   - classList includes "mw-page-chip-popover--mobile"
 *   - position: fixed; zIndex: 100000
 *   - dark-token bg resolves (rgb(30, 36, 50)) — token-scoping safe
 *   - inside-click does NOT close ($refs.popover.contains works)
 */
class LiveEditPchip01PageChipMobileTeleportContractTest extends TestCase
{
    private string $vue;
    private string $vueStripped;
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));

        // Layer 1 belt: strip JS/Vue line + block comments so absence
        // assertions don't false-match against docblock prose.
        $this->vueStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->vue);
        $this->vueStripped = (string) preg_replace('~//[^\n]*~', '', $this->vueStripped);
        $this->vueStripped = (string) preg_replace('~<!--.*?-->~s', '', $this->vueStripped);
    }

    #[Test]
    public function popover_wrapped_in_teleport_to_body_disabled_on_desktop(): void
    {
        // Path A signature: the popover root sits inside
        // <Teleport to="body" :disabled="!isMobile"> ... </Teleport>.
        $this->assertMatchesRegularExpression(
            '/<Teleport\s+to="body"\s+:disabled="!isMobile">\s*<div[\s\S]*?class="mw-page-chip-popover"/',
            $this->vueStripped,
            'PageChip popover must be wrapped in <Teleport to="body" :disabled="!isMobile"> so it escapes the hidden .toolbar-col-container on mobile while staying in-place on desktop.'
        );

        $this->assertMatchesRegularExpression(
            '~<Teleport[\s\S]*?</Teleport>~',
            $this->vueStripped,
            'Teleport block must be closed.'
        );
    }

    #[Test]
    public function popover_carries_mobile_class_binding(): void
    {
        // The teleported popover wears .mw-page-chip-popover--mobile
        // so live-edit-mobile.css can target the overlay shape.
        $this->assertMatchesRegularExpression(
            "/'mw-page-chip-popover--mobile':\s*isMobile/",
            $this->vueStripped,
            'Popover must carry the "mw-page-chip-popover--mobile" class when isMobile is true.'
        );
    }

    #[Test]
    public function ismobile_data_field_and_matchmedia_state_wired(): void
    {
        $this->assertMatchesRegularExpression(
            '/isMobile:\s*false/',
            $this->vueStripped,
            'data() must declare isMobile: false (matchMedia flips it on mount).'
        );
        $this->assertMatchesRegularExpression(
            '/_mqList:\s*null/',
            $this->vueStripped,
            'data() must declare _mqList: null for matchMedia state.'
        );
        $this->assertMatchesRegularExpression(
            '/_mqHandler:\s*null/',
            $this->vueStripped,
            'data() must declare _mqHandler: null for matchMedia state.'
        );
    }

    #[Test]
    public function matchmedia_listener_wired_to_breakpoint(): void
    {
        // matchMedia('(max-width: 768px)') mirrors live-edit-mobile.css
        // @media (max-width: 768px), (pointer: coarse) breakpoint so
        // viewport rotation/resize flips isMobile reactively.
        $this->assertMatchesRegularExpression(
            "/window\.matchMedia\(\s*'\(max-width:\s*768px\)'\s*\)/",
            $this->vueStripped,
            'mounted() must wire matchMedia("(max-width: 768px)") to drive isMobile.'
        );
        $this->assertMatchesRegularExpression(
            '/this\.isMobile\s*=\s*!!this\._mqList\.matches/',
            $this->vueStripped,
            'mounted() must initialise this.isMobile from this._mqList.matches.'
        );
        $this->assertMatchesRegularExpression(
            "/_mqList\.addEventListener\(\s*'change'\s*,\s*this\._mqHandler\s*\)/",
            $this->vueStripped,
            'mounted() must subscribe via addEventListener("change", ...).'
        );
    }

    #[Test]
    public function matchmedia_cleanup_symmetric_across_both_unmount_hooks(): void
    {
        // Vue 2 fires beforeDestroy, Vue 3 fires beforeUnmount.
        // Both must remove the matchMedia listener to avoid leaks
        // (component recreate on hot-reload or route change).
        $beforeUnmountSlice = $this->extractMethodBody('beforeUnmount');
        $beforeDestroySlice = $this->extractMethodBody('beforeDestroy');

        $this->assertNotEmpty($beforeUnmountSlice, 'beforeUnmount hook must exist.');
        $this->assertNotEmpty($beforeDestroySlice, 'beforeDestroy hook must exist.');

        foreach (['beforeUnmount' => $beforeUnmountSlice, 'beforeDestroy' => $beforeDestroySlice] as $hook => $slice) {
            $this->assertMatchesRegularExpression(
                '/this\._mqList\s*&&\s*this\._mqHandler/',
                $slice,
                $hook . '() must guard on this._mqList && this._mqHandler before removing the listener.'
            );
            $this->assertMatchesRegularExpression(
                "/_mqList\.removeEventListener\(\s*'change'\s*,\s*this\._mqHandler\s*\)/",
                $slice,
                $hook . '() must remove the matchMedia listener via removeEventListener.'
            );
        }
    }

    #[Test]
    public function compute_anchor_short_circuits_on_mobile(): void
    {
        // On mobile, the popover is position:fixed full-viewport so
        // the chipRect-based horizontal flip is irrelevant. The method
        // must early-return after setting popoverAnchor = 'center'.
        $sliceStart = strpos($this->vueStripped, 'computeAnchor');
        $this->assertNotFalse($sliceStart, 'computeAnchor() method must exist.');

        $slice = substr($this->vueStripped, $sliceStart, 800);

        $this->assertMatchesRegularExpression(
            "/if\s*\(\s*this\.isMobile\s*\)\s*\{[^}]*this\.popoverAnchor\s*=\s*'center';\s*return;/",
            $slice,
            'computeAnchor() must short-circuit on mobile (set popoverAnchor=center, return).'
        );
    }

    #[Test]
    public function outside_click_handler_accounts_for_teleported_popover(): void
    {
        // When the popover is teleported, root.contains(target) is
        // false for clicks inside the popover. Without the extra
        // $refs.popover.contains check, the popover would dismiss
        // itself on every inside click.
        $sliceStart = strpos($this->vueStripped, 'onOutsideClick');
        $this->assertNotFalse($sliceStart, 'onOutsideClick() handler must exist.');

        $slice = substr($this->vueStripped, $sliceStart, 800);

        $this->assertMatchesRegularExpression(
            '/var\s+popover\s*=\s*this\.\$refs\.popover/',
            $slice,
            'onOutsideClick() must capture this.$refs.popover for the teleported-popover containment check.'
        );
        $this->assertMatchesRegularExpression(
            '/popover\s*&&\s*popover\.contains\(\s*event\.target\s*\)/',
            $slice,
            'onOutsideClick() must early-return when $refs.popover contains the click target.'
        );
    }

    #[Test]
    public function mwopenpagechip_handler_still_wired(): void
    {
        // The MainDrawer "Pages" item dispatches mwOpenPageChip
        // CustomEvent on window — PageChip must still listen so the
        // mobile bridge works end-to-end.
        $this->assertMatchesRegularExpression(
            "/window\.addEventListener\(\s*'mwOpenPageChip'/",
            $this->vueStripped,
            'PageChip must still listen for the mwOpenPageChip CustomEvent dispatched by MainDrawer.'
        );
    }

    #[Test]
    public function mobile_overlay_css_inside_max768_media_block(): void
    {
        // The position:fixed overlay rule must live INSIDE the
        // canonical @media (max-width: 768px), (pointer: coarse)
        // block — desktop must NOT receive this rule.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?\.mw-page-chip-popover\.mw-page-chip-popover--mobile\s*\{[^}]*position:\s*fixed\s*!important/s',
            $this->css,
            'Mobile-overlay rule for .mw-page-chip-popover--mobile must live INSIDE the @media (max-width: 768px), (pointer: coarse) block with position:fixed !important.'
        );
    }

    #[Test]
    public function mobile_overlay_inset_z_index_and_size_constraints(): void
    {
        // Pin the geometry: top 56px (toolbar 48 + 8 margin),
        // inset 8px on the other three sides, z-index 100000,
        // max-height calc(100vh - 64px) for safe-area bottom.
        $this->assertStringContainsString('top: 56px !important', $this->css);
        $this->assertStringContainsString('left: 8px !important', $this->css);
        $this->assertStringContainsString('right: 8px !important', $this->css);
        $this->assertStringContainsString('bottom: 8px !important', $this->css);
        $this->assertStringContainsString('z-index: 100000 !important', $this->css);
        $this->assertStringContainsString('max-height: calc(100vh - 64px) !important', $this->css);
        $this->assertStringContainsString('width: auto !important', $this->css);
        $this->assertStringContainsString('max-width: none !important', $this->css);
        $this->assertStringContainsString('transform: none !important', $this->css);
    }

    #[Test]
    public function task_markers_present_in_source_and_css(): void
    {
        // Audit trail.
        $this->assertStringContainsString('task-2026-05-30-pchip01', $this->vue);
        $this->assertStringContainsString('task-2026-05-30-pchip01', $this->css);
    }

    #[Test]
    public function teleport_disabled_signature_pinned_no_unconditional_teleport(): void
    {
        // Regression guard: prevent a future hand from dropping the
        // :disabled toggle (which would teleport on desktop too, in
        // violation of SOUL #108). The exact substring must persist.
        $this->assertStringContainsString(
            '<Teleport to="body" :disabled="!isMobile">',
            $this->vue,
            'The Teleport must remain conditional via :disabled="!isMobile" — never unconditional.'
        );
    }

    /**
     * Balance-counting source-slice helper (canonical pattern lifted
     * from Content4e9d1bAI792DefaultBlogPageLayoutContractTest).
     * Returns the body of a Vue option (e.g. beforeUnmount, mounted)
     * by walking { } depth from the opening brace until depth returns
     * to 0. Robust against nested closures / object literals.
     */
    private function extractMethodBody(string $name): string
    {
        $offset = 0;
        $needle = $name;

        while (($pos = strpos($this->vueStripped, $needle, $offset)) !== false) {
            // Look for an opening '{' shortly after the name token
            // skipping the "(...)" parameter list.
            $openParen = strpos($this->vueStripped, '(', $pos);
            $closeParen = $openParen !== false ? strpos($this->vueStripped, ')', $openParen) : false;
            $openBrace = $closeParen !== false ? strpos($this->vueStripped, '{', $closeParen) : false;
            if ($openBrace === false || $openBrace - $pos > 200) {
                $offset = $pos + strlen($needle);
                continue;
            }

            $depth = 0;
            $len = strlen($this->vueStripped);
            for ($i = $openBrace; $i < $len; $i++) {
                $ch = $this->vueStripped[$i];
                if ($ch === '{') {
                    $depth++;
                } elseif ($ch === '}') {
                    $depth--;
                    if ($depth === 0) {
                        return substr($this->vueStripped, $openBrace, $i - $openBrace + 1);
                    }
                }
            }

            $offset = $pos + strlen($needle);
        }

        return '';
    }
}
