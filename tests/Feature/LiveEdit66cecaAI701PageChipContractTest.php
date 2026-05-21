<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-66ceca / AI-701 (Medium) — replace the centred
 * generic Search field with a current-page chip + picker per
 * designer's v2 anchored-page-name-dropdown spec.
 *
 * Designer dispatch (live-edit-inspiration-from-v2-2026-05-16.md
 * §2 P6, per-ticket email 2026-05-16T13:39):
 *
 *   - Centred toolbar slot becomes a <button class="mw-page-chip">
 *     showing the current page name (truncate ~24 chars + ellipsis)
 *     with a `⌄` suffix.
 *   - Click opens a popover containing:
 *       - search input (filters pages)
 *       - the page tree (list of recent pages from
 *         get_content_admin)
 *       - `+ New page` shortcut
 *   - Chip: bg `var(--ese-surface-muted)`, radius `var(--radius-sm)`,
 *     padding `var(--space-xs) var(--space-md)`, font
 *     `var(--font-control)`.
 *   - Reuse the MwField token pattern (AI-687) for the popover's
 *     search input — AI-687 hasn't shipped, so a basic styled
 *     <input> with the same ESE tokens lands now and refactors
 *     when AI-687 lands.
 *
 * Dependency note: dispatch flagged "After AI-698" (toolbar
 * restructure positions the centre slot). The chip works in
 * BOTH the current toolbar layout AND the post-AI-698c layout
 * (the slot itself is positional; the chip is layout-agnostic).
 * Shipping AI-701 in the current layout slot — when AI-698c
 * restructures, the chip moves with the slot.
 *
 * Implementation (3 files):
 *
 *   1. PageChip.vue (NEW) — chip button + popover with search
 *      input, results list, "+ New page" footer. Current page
 *      title read from `mw.top().app.canvas.getLiveEditData().
 *      content.title`. Outside-click + ESC close. Reuses the
 *      same `get_content_admin` endpoint ContentSearchNav.vue
 *      used.
 *
 *   2. Toolbar.vue — replace <ContentSearchNav> with <PageChip>
 *      in the centred slot. The legacy ContentSearchNav DOM is
 *      preserved (display: none aria-hidden wrapper) so external
 *      code targeting `#mw-live-edit-search-content` still
 *      finds it.
 *
 *   3. general-styles.css — chip + popover + dark-theme rules
 *      using ESE tokens with literal fallbacks. prefers-reduced-
 *      motion disables the chevron rotate animation.
 */
class LiveEdit66cecaAI701PageChipContractTest extends TestCase
{
    private string $pageChip;
    private string $toolbar;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pageChip = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        ));
        $this->toolbar = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/Toolbar.vue'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — PageChip component shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function chip_button_renders_truncated_title_and_chevron(): void
    {
        // Truncated label via the computed prop.
        $this->assertMatchesRegularExpression(
            '/<span class="mw-page-chip__label">\{\{ currentPageTitleTruncated \|\| \'Untitled\' \}\}<\/span>/',
            $this->pageChip,
            'Chip label must render currentPageTitleTruncated (computed prop) with "Untitled" fallback.'
        );
        // Chevron SVG inside the button.
        $this->assertMatchesRegularExpression(
            '/class="mw-page-chip__chevron"/',
            $this->pageChip,
            'Chip must render a chevron SVG via .mw-page-chip__chevron.'
        );
    }

    #[Test]
    public function truncation_uses_24_char_limit(): void
    {
        // Designer spec: ~24 chars + ellipsis.
        $this->assertMatchesRegularExpression(
            '/currentPageTitleTruncated\(\)\s*\{[\s\S]*?if\s*\(t\.length\s*<=\s*24\)\s*return\s+t;[\s\S]*?return\s+t\.substr\(0,\s*24\)\s*\+\s*[\'"]\xe2\x80\xa6[\'"]/',
            $this->pageChip,
            'currentPageTitleTruncated must cap at 24 chars + Unicode ellipsis per designer spec.'
        );
    }

    #[Test]
    public function chip_open_state_binds_isopen(): void
    {
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{ 'mw-page-chip--open': isOpen \\}\"/",
            $this->pageChip,
            'Chip must toggle the mw-page-chip--open class based on isOpen state.'
        );
        $this->assertMatchesRegularExpression(
            "/:aria-expanded=\"isOpen \\? 'true' : 'false'\"/",
            $this->pageChip,
            'Chip must bind aria-expanded to isOpen for AT users.'
        );
    }

    #[Test]
    public function current_page_title_pulled_from_canvas_data(): void
    {
        $this->assertMatchesRegularExpression(
            '/readCurrentPageTitle\(\)\s*\{[\s\S]*?top\.app\.canvas\.getLiveEditData\(\)[\s\S]*?data\.content\.title/',
            $this->pageChip,
            'readCurrentPageTitle must read from mw.top().app.canvas.getLiveEditData().content.title (matches Toolbar.vue pattern).'
        );
        // Listens for canvas-load to refresh title on navigation.
        $this->assertStringContainsString(
            "window.mw.top().app.on('liveEditCanvasLoaded'",
            $this->pageChip,
            'PageChip must listen for liveEditCanvasLoaded to refresh title on page switch.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Popover shape + handlers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function popover_has_dialog_role_and_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+v-show="isOpen"[\s\S]*?role="dialog"[\s\S]*?aria-label="Switch page"/',
            $this->pageChip,
            'Popover must carry role="dialog" + aria-label="Switch page".'
        );
        // Inline default-hidden style per LESSONS (no global [x-cloak]).
        $this->assertMatchesRegularExpression(
            '/class="mw-page-chip-popover"[\s\S]*?style="display: none;"/',
            $this->pageChip,
            'Popover must carry inline display: none default — LESSONS (no global [x-cloak]).'
        );
    }

    #[Test]
    public function popover_search_input_pinned(): void
    {
        // task-2026-05-18-fd85d0 pin-evolution: @input handler renamed
        // from "search" to "onSearchInput" to distinguish from the
        // method that triggers debounced search.
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]*?type="search"[\s\S]*?class="mw-page-chip-popover__input"[\s\S]*?v-model="q"[\s\S]*?@input="onSearchInput"/',
            $this->pageChip,
            'Popover search input must use v-model="q" + @input="onSearchInput" (debounced via setTimeout).'
        );
        // aria-label present (now dynamic: :aria-label="'Search ' + activeTabLabel")
        $this->assertMatchesRegularExpression(
            '/:aria-label="\'Search \' \+ activeTabLabel"/',
            $this->pageChip,
            'Search input aria-label must be dynamic: :aria-label="\'Search \' + activeTabLabel".'
        );
    }

    #[Test]
    public function popover_results_list_pinned(): void
    {
        $this->assertMatchesRegularExpression(
            '/<ul\s+class="mw-page-chip-popover__list"\s+v-if="results\.length">[\s\S]*?<li\s+v-for="item in results"/',
            $this->pageChip,
            'Popover must render a results list (v-if="results.length") with v-for="item in results".'
        );
        // task-2026-05-18-fd85d0 pin-evolution: empty state text is now
        // dynamic ("No <tab> found." / "No <tab> yet.") — check the
        // condition shape.
        $this->assertMatchesRegularExpression(
            "/v-else-if=\"isLoading\"/",
            $this->pageChip,
            'Popover must render a loading state while fetching.'
        );
        $this->assertMatchesRegularExpression(
            "/v-else-if=\"q !== '' \|\| hasLoaded\"/",
            $this->pageChip,
            'Popover must render empty state when no results and already loaded or q is non-empty.'
        );
    }

    #[Test]
    public function popover_new_page_shortcut_pinned(): void
    {
        // task-2026-05-18-fd85d0 pin-evolution: CTA href now uses
        // newItemHref (per-tab create URL); newPageHref is a back-compat
        // alias that delegates to newItemHref.
        $this->assertMatchesRegularExpression(
            '/:href="newItemHref"[\s\S]*?class="mw-page-chip-popover__new-page"/',
            $this->pageChip,
            'Popover footer must contain a "+ New <type>" anchor wired to newItemHref.'
        );
        // newItemHref uses the correct Filament create routes (not the
        // legacy admin_url('content/create?content_type=X') which 404s)
        $this->assertMatchesRegularExpression(
            '/newItemHref\(\)\s*\{[\s\S]*?pages\/create[\s\S]*?posts\/create[\s\S]*?products\/create/',
            $this->pageChip,
            'newItemHref must map page/post/product to their Filament create routes (/admin/pages/create etc).'
        );
        // Back-compat alias preserved for external code that may reference newPageHref
        $this->assertStringContainsString(
            'newPageHref()',
            $this->pageChip,
            'newPageHref() back-compat alias must remain.'
        );
    }

    #[Test]
    public function search_uses_same_endpoint_as_legacy_content_search_nav(): void
    {
        // Continuity with the prior ContentSearchNav — both call
        // mw.settings.api_url + 'get_content_admin?...&keyword=...'
        // task-2026-05-18-fd85d0 pin-evolution: fetchResults now accepts
        // a callback + uses this.activeTab for content_type.
        $this->assertMatchesRegularExpression(
            "/fetchResults\\(keyword,\\s*callback\\)\\s*\\{[\\s\\S]*?get_content_admin\\?get_extra_data=1/",
            $this->pageChip,
            'fetchResults must call the same get_content_admin endpoint ContentSearchNav.vue used (continuity with legacy behaviour).'
        );
        // Now filters by this.activeTab (dynamic per tab)
        $this->assertStringContainsString(
            'this.activeTab',
            $this->pageChip,
            'fetchResults must filter by this.activeTab (pages/posts/products — not hard-coded content_type=page).'
        );
    }

    #[Test]
    public function outside_click_and_escape_close_popover(): void
    {
        $this->assertMatchesRegularExpression(
            "/onOutsideClick\\(event\\)\\s*\\{[\\s\\S]*?if\\s*\\(!root\\.contains\\(event\\.target\\)\\)\\s*\\{[\\s\\S]*?this\\.close\\(\\)/",
            $this->pageChip,
            'onOutsideClick must close the popover when the click target is outside the chip wrapper.'
        );
        $this->assertMatchesRegularExpression(
            "/onKeyDown\\(event\\)\\s*\\{[\\s\\S]*?event\\.key\\s*===\\s*'Escape'[\\s\\S]*?this\\.close\\(\\)/",
            $this->pageChip,
            'onKeyDown must close the popover on ESC key.'
        );
        // Listeners registered + removed
        $this->assertStringContainsString(
            "document.addEventListener('click', this._outsideHandler, true)",
            $this->pageChip,
            'Outside-click handler must register on document with capture=true (so it catches clicks BEFORE inner stopPropagation).'
        );
        $this->assertStringContainsString(
            "document.removeEventListener('click', this._outsideHandler, true)",
            $this->pageChip,
            'Outside-click handler must be removed on unmount.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Toolbar.vue wiring
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function toolbar_imports_and_registers_page_chip(): void
    {
        $this->assertStringContainsString(
            'import PageChip from "./PageChip.vue"',
            $this->toolbar,
            'Toolbar.vue must import PageChip.'
        );
        $this->assertMatchesRegularExpression(
            '/components:\s*\{[^}]*PageChip/s',
            $this->toolbar,
            'PageChip must be registered in Toolbar.vue components.'
        );
    }

    #[Test]
    public function toolbar_renders_page_chip_in_centre_slot(): void
    {
        $this->assertMatchesRegularExpression(
            '/<PageChip><\/PageChip>/',
            $this->toolbar,
            'Toolbar.vue must render <PageChip></PageChip> in the centred slot.'
        );
    }

    #[Test]
    public function legacy_content_search_nav_preserved_hidden(): void
    {
        // External code may target #mw-live-edit-search-content;
        // keep the legacy DOM hook as a hidden fallback.
        $this->assertStringContainsString(
            '<ContentSearchNav>',
            $this->toolbar,
            'Legacy ContentSearchNav must remain mounted (for external code that targets #mw-live-edit-search-content).'
        );
        // The wrapper must be aria-hidden + display: none.
        $this->assertMatchesRegularExpression(
            '/<div\s+style="display: none;"\s+aria-hidden="true">\s*<ContentSearchNav>/',
            $this->toolbar,
            'Legacy ContentSearchNav must be wrapped in display: none + aria-hidden="true".'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — CSS shape (chip + popover)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function chip_css_uses_surface_muted_bg_and_radius_sm(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip\s*\{[^}]*background-color:\s*var\(--ese-surface-muted,\s*rgba\(0,\s*0,\s*0,\s*0\.04\)\)[^}]*color:\s*var\(--ese-text,\s*#111827\)[^}]*border-radius:\s*var\(--radius-sm,\s*6px\)/s',
            $this->generalStyles,
            'Chip must use --ese-surface-muted bg, --ese-text foreground, --radius-sm rounding (with literal fallbacks).'
        );
    }

    #[Test]
    public function chip_css_padding_uses_phi_tokens(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip\s*\{[^}]*padding:\s*var\(--space-xs,\s*6px\)\s+var\(--space-md,\s*13px\)/s',
            $this->generalStyles,
            'Chip padding must use --space-xs --space-md tokens per designer spec.'
        );
    }

    #[Test]
    public function chevron_rotates_when_open(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip--open\s+\.mw-page-chip__chevron\s*\{[^}]*transform:\s*rotate\(180deg\)/s',
            $this->generalStyles,
            'Chevron must rotate 180deg when the chip is open.'
        );
    }

    #[Test]
    public function popover_centred_below_chip(): void
    {
        // top: 100% + var(--space-xs) → just below the chip.
        // left: 50% + translateX(-50%) → centred on the chip width.
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover\s*\{[^}]*position:\s*absolute[^}]*top:\s*calc\(100%\s*\+\s*var\(--space-xs,\s*6px\)\)[^}]*left:\s*50%[^}]*transform:\s*translateX\(-50%\)/s',
            $this->generalStyles,
            'Popover must anchor below the chip (top: calc(100% + space-xs)) and centre horizontally (left: 50% + translateX(-50%)).'
        );
    }

    #[Test]
    public function popover_search_input_uses_mwfield_token_pattern(): void
    {
        // The MwField pattern is "input on --ese-surface bg with
        // --ese-border + --radius-sm + --space-sm/--space-md padding
        // + focus-visible accent outline". When AI-687 ships, the
        // <input> can be wrapped in <MwField>; for now the bare
        // <input> consumes the same tokens.
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__input\s*\{[^}]*background-color:\s*var\(--ese-surface,\s*#ffffff\)[^}]*border:\s*1px\s+solid\s+var\(--ese-border[^}]*border-radius:\s*var\(--radius-sm,\s*6px\)/s',
            $this->generalStyles,
            'Popover input must consume the MwField token pattern (--ese-surface bg, --ese-border border, --radius-sm rounding).'
        );
        // Focus-visible outline uses --ese-accent.
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__input:focus-visible\s*\{[^}]*outline:\s*2px\s+solid\s+var\(--ese-accent/s',
            $this->generalStyles,
            'Popover input focus-visible must use --ese-accent outline.'
        );
    }

    #[Test]
    public function prefers_reduced_motion_disables_chevron_transition(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?\.mw-page-chip[\s\S]*?transition:\s*none/s',
            $this->generalStyles,
            'prefers-reduced-motion must disable the chip/chevron transitions (SOUL #108 designer motion pattern).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_all_three_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-66ceca', $this->pageChip);
        $this->assertStringContainsString('task-2026-05-16-66ceca', $this->toolbar);
        $this->assertStringContainsString('task-2026-05-16-66ceca', $this->generalStyles);
    }

    #[Test]
    public function ai_687_mwfield_followup_documented(): void
    {
        // Designer spec asked for MwField reuse; AI-687 isn't shipped
        // so the spec deferral is documented in source for the next
        // agent.
        $this->assertStringContainsString(
            'AI-687',
            $this->pageChip,
            'PageChip.vue must reference AI-687 (MwField) in the docblock so the followup-to-MwField refactor opportunity is discoverable from source.'
        );
        $this->assertStringContainsString(
            'AI-687',
            $this->generalStyles
        );
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        // Slice from the AI-701 marker to the end of the file. The
        // chip + popover slice consumes 8+ ESE tokens, every one
        // with a literal fallback per SOUL #108.
        $start = strpos($this->generalStyles, 'AI-701 — current-page chip + picker');
        $this->assertNotFalse($start, 'AI-701 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);
        $tokens = [
            '--ese-surface-muted' => 'rgba(0, 0, 0, 0.04)',
            '--ese-surface-hover' => 'rgba(0, 0, 0, 0.08)',
            '--ese-surface'       => '#ffffff',
            '--ese-text'          => '#111827',
            '--ese-text-muted'    => '#6b7280',
            '--ese-accent'        => '#0d6efd',
            '--ese-border'        => 'rgba(0, 0, 0, 0.12)',
            '--space-xs'          => '6px',
            '--space-sm'          => '8px',
            '--space-md'          => '13px',
            '--font-control'      => '13px',
            '--radius-sm'         => '6px',
            '--radius-md'         => '10px',
            '--t-fast'            => '120ms',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-701 slice."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — task-2026-05-18-fd85d0 additions
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function tab_bar_present_with_pages_posts_products(): void
    {
        // task-2026-05-18-fd85d0: tablist with Pages / Posts / Products.
        $this->assertMatchesRegularExpression(
            '/role="tablist"/',
            $this->pageChip,
            'Popover must carry role="tablist" for the tab bar.'
        );
        foreach (['Pages', 'Posts', 'Products'] as $label) {
            $this->assertStringContainsString(
                $label,
                $this->pageChip,
                "Tab bar must include a '{$label}' tab."
            );
        }
        $this->assertMatchesRegularExpression(
            '/tabs:\s*\[[\s\S]*?key:\s*[\'"]page[\'"][\s\S]*?key:\s*[\'"]post[\'"][\s\S]*?key:\s*[\'"]product[\'"]/s',
            $this->pageChip,
            'Tabs data array must define page, post, product keys in that order.'
        );
    }

    #[Test]
    public function load_recent_called_on_open(): void
    {
        // task-2026-05-18-fd85d0: recent content loaded immediately on
        // open, not only after the user types a query.
        $this->assertMatchesRegularExpression(
            '/open\(\)\s*\{[\s\S]*?this\.loadRecent\(\)/s',
            $this->pageChip,
            'open() must call this.loadRecent() to populate recent content immediately.'
        );
        $this->assertMatchesRegularExpression(
            '/loadRecent\(\)\s*\{[\s\S]*?this\.isLoading\s*=\s*true/s',
            $this->pageChip,
            'loadRecent() must set isLoading=true before fetching.'
        );
    }

    #[Test]
    public function popover_border_visible_in_dark_mode(): void
    {
        // task-2026-05-18-fd85d0: border must be visible against the dark
        // toolbar. The original rgba(0,0,0,0.12) was invisible.
        // New implementation uses a solid colour rather than transparent.
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover\s*\{[^}]*border:\s*1\.5px\s+solid/s',
            $this->generalStyles,
            'Popover must use a solid 1.5px border (not rgba-transparent) so it is visible against the dark toolbar.'
        );
        // Dark-mode override with visible border colour.
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.mw-page-chip-popover\s*\{[^}]*border-color:/s',
            $this->generalStyles,
            'html.dark .mw-page-chip-popover must have an explicit border-color override.'
        );
    }

    #[Test]
    public function tab_css_rules_present(): void
    {
        // task-2026-05-18-fd85d0: tab bar CSS.
        $this->assertStringContainsString(
            '.mw-page-chip-popover__tabs',
            $this->generalStyles,
            'general-styles.css must include .mw-page-chip-popover__tabs rule.'
        );
        $this->assertStringContainsString(
            '.mw-page-chip-popover__tab',
            $this->generalStyles,
            'general-styles.css must include .mw-page-chip-popover__tab rule.'
        );
        $this->assertStringContainsString(
            '.mw-page-chip-popover__tab--active',
            $this->generalStyles,
            'general-styles.css must include .mw-page-chip-popover__tab--active rule.'
        );
    }

    #[Test]
    public function task_fd85d0_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-18-fd85d0',
            $this->pageChip,
            'PageChip.vue must carry the task-fd85d0 marker.'
        );
        $this->assertStringContainsString(
            'task-2026-05-18-fd85d0',
            $this->generalStyles,
            'general-styles.css must carry the task-fd85d0 marker.'
        );
    }
}
