<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-aeb113 / AI-806 + AI-807 + AI-808 Page template
 * product-chrome leak fix (designer-authorised bundle, Round 13.2).
 *
 * Jira: https://microweber.atlassian.net/browse/AI-806
 *       https://microweber.atlassian.net/browse/AI-807
 *       https://microweber.atlassian.net/browse/AI-808
 *
 * Pre-fix Modules/Page/resources/views/templates/default.blade.php was
 * a near-verbatim copy of an old Products template. Six distinct
 * defects from the single copy-paste -- pinned individually here as
 * negative regression guards so future copy-paste cannot drift back
 * to the products chrome:
 *
 *   1. mw.require('shop.js') loaded unconditionally for Pages lists.
 *   2. data-mw-cart-add-and-checkout button rendered Pages as cartable.
 *   3. span.price + currency_format() rendered for pages with prices.
 *   4. .module-products-template-columns-3 CSS class cascaded all
 *      .module-products-* rules into Pages.
 *   5. itemtype dynamically resolved to schema.org/Product (Google
 *      indexed pages as Products).
 *   6. <?php print $item['...'] ?> -- unescaped PHP echo. AI-807 XSS.
 *
 * Plus AI-808: no admin-only empty state -- adopt AI-780a pattern with
 * content_type='page' explicit (no inference, this template renders
 * Pages by definition).
 *
 * Selector-self-match guard family (now 16+ session-recurrences):
 * the docblock above legitimately mentions every pre-fix string. All
 * negative regression assertions slice the template's `<style>` +
 * `<div>` body via strpos past the closing `@endphp` of the docblock
 * before scanning, so the docblock's prose never false-fails the
 * absence asserts.
 */
class PageAeb113AI806ProductChromeLeakContractTest extends TestCase
{
    private string $template;

    /**
     * Source SLICED past the closing @endphp of the header docblock
     * (the docblock describes every pre-fix legacy string by name).
     * Negative regression assertions run on this slice only.
     */
    private string $executable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->template = (string) file_get_contents(base_path(
            'Modules/Page/resources/views/templates/default.blade.php'
        ));

        // Slice past the header docblock's @endphp so absence asserts
        // never false-fail on the docblock's mention of legacy tokens.
        $endOfHeader = strpos($this->template, '@endphp');
        $this->executable = $endOfHeader === false
            ? $this->template
            : substr($this->template, $endOfHeader + 7);

        // ALSO strip Blade `{{-- ... --}}` comments from the executable
        // slice -- inline section docblocks mention pre-fix legacy
        // strings (e.g. the AI-807 inline comment quotes the legacy
        // unescaped php-print-of-item pattern to explain what was
        // removed). NOTE: never write a literal php-close-tag (the
        // two-character sequence question-mark + greater-than) inside
        // any `//` or `/* */` comment in a `.php` file -- PHP exits
        // code mode at that sequence even mid-comment (recurring
        // session lesson; same parser-meaningful-character family as
        // the docblock-terminating `*/` rule from AI-790).
        $this->executable = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $this->executable);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  AI-806 6-defect negative regression guards
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function defect_1_shop_js_require_is_gone(): void
    {
        $this->assertStringNotContainsString(
            "mw.require('shop.js')",
            $this->executable,
            'Pages list MUST NOT load shop.js -- it is product-chrome leakage from the prior Products copy-paste.'
        );
    }

    #[Test]
    public function defect_2_cart_add_and_checkout_button_is_gone(): void
    {
        $this->assertStringNotContainsString(
            'data-mw-cart-add-and-checkout',
            $this->executable,
            'Page list MUST NOT carry data-mw-cart-add-and-checkout -- Pages cannot be added to cart.'
        );
    }

    #[Test]
    public function defect_3_price_span_and_currency_format_are_gone(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<span\s+class="price">/i',
            $this->executable,
            'Page list MUST NOT render <span class="price"> -- Pages do not have prices.'
        );
        $this->assertStringNotContainsString(
            'currency_format(',
            $this->executable,
            'Page list MUST NOT call currency_format() -- no price chrome on Pages.'
        );
    }

    #[Test]
    public function defect_4_module_products_class_cascade_is_gone(): void
    {
        $this->assertStringNotContainsString(
            'module-products-template-columns-3',
            $this->executable,
            'Page template MUST NOT carry the .module-products-template-columns-3 hook -- it cascades all .module-products-* rules into Pages.'
        );
        $this->assertStringNotContainsString(
            'mw-module-products-default-item',
            $this->executable,
            'Page template MUST NOT carry .mw-module-products-default-item -- product-specific styling cascade.'
        );
        // Positive: the new shape uses .module-pages-* prefix.
        $this->assertStringContainsString(
            'module-pages-template-columns-3',
            $this->executable,
            'Page template MUST use .module-pages-template-columns-3 hook (rebrand of the columns helper).'
        );
    }

    #[Test]
    public function defect_5_schema_org_product_leak_is_gone(): void
    {
        // The dynamic $schema_org_item_type_tag defaults to Product when
        // options['content_type'] is unset -- PageModule never injects
        // it, so this template MUST NOT reference the variable.
        $this->assertStringNotContainsString(
            '$schema_org_item_type_tag',
            $this->executable,
            'Page template MUST NOT reference $schema_org_item_type_tag -- it defaults to schema.org/Product when PageModule does not inject content_type=page.'
        );
        // Positive: hard-coded WebPage schema.
        $this->assertMatchesRegularExpression(
            '/itemtype="https:\/\/schema\.org\/WebPage"/',
            $this->executable,
            'Page template MUST hard-code itemtype="https://schema.org/WebPage".'
        );
        // Negative: no Product schema URL appears anywhere in the body.
        $this->assertDoesNotMatchRegularExpression(
            '/schema\.org\/Product\b/',
            $this->executable,
            'Page template MUST NOT carry any schema.org/Product reference.'
        );
    }

    #[Test]
    public function defect_6_hidden_cart_form_inputs_are_gone(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<input[^>]*name="price"/',
            $this->executable,
            'Page template MUST NOT carry <input name="price"> -- cart form leakage.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<input[^>]*name="content_id"/',
            $this->executable,
            'Page template MUST NOT carry <input name="content_id"> -- cart form leakage.'
        );
        $this->assertStringNotContainsString(
            'mw-add-to-cart-',
            $this->executable,
            'Page template MUST NOT carry .mw-add-to-cart-* anchor classes.'
        );
        $this->assertStringNotContainsString(
            'products-list-proceholder',
            $this->executable,
            'Page template MUST NOT carry .products-list-proceholder.'
        );
        $this->assertStringNotContainsString(
            'product-price-holder',
            $this->executable,
            'Page template MUST NOT carry .product-price-holder.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  AI-807 XSS regression guards (no unescaped raw-php-echo)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai807_no_unescaped_php_print_of_item_fields(): void
    {
        // Pre-fix every $item field was emitted via a raw php-print of
        // $item[...] which does not escape HTML. New template uses
        // Blade double-curly which auto-escapes via htmlspecialchars.
        $this->assertDoesNotMatchRegularExpression(
            '/<\?php\s+print\s+\$item\[/',
            $this->executable,
            'AI-807: Page template MUST NOT carry any `<?php print $item[...]` unescaped echo -- use Blade {{ }} which auto-escapes.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<\?=\s*\$item\[/',
            $this->executable,
            'AI-807: Page template MUST NOT use short-echo `<?= $item[...]` either -- those are also unescaped.'
        );
    }

    #[Test]
    public function ai807_no_raw_php_blocks_at_all(): void
    {
        // Belt-and-braces: the new template is pure Blade. No raw php
        // open tags anywhere in the executable body (legacy template
        // was 100% raw php). The only allowed source-side legacy
        // token would be inside a Blade `{{-- comment --}}` describing
        // the removed pattern -- which we already pre-stripped.
        $this->assertDoesNotMatchRegularExpression(
            '/<\?php\b/',
            $this->executable,
            'AI-807: Page template MUST be pure Blade -- no raw php blocks (legacy template was 100% raw php).'
        );
    }

    #[Test]
    public function ai807_designer_literal_regex_stub_passes_against_raw_source(): void
    {
        /* task-2026-05-17-3937be / AI-807 -- belt-and-braces. The
         * designer email shipped a literal regex stub: the assertion
         * here matches that stub VERBATIM against the RAW source (no
         * comment-stripping). This guarantees the designer (or any
         * future agent) running the literal stub from the JIRA ticket
         * body gets the same green that this PHPUnit run does. Adds
         * a second-layer regression guard: future docblock prose that
         * accidentally re-introduces the legacy token in comments
         * will fail this test even though Group B's stripped-source
         * assertion would tolerate it.
         *
         * Selector-self-match guard family (16+ session-recurrences):
         * documented in LESSONS.md. The fix here is to phrase source
         * prose so it never carries the literal absence-asserted token,
         * EVEN inside comments -- so the literal designer stub passes
         * without needing the contract test to pre-strip comments. */
        $this->assertDoesNotMatchRegularExpression(
            '/<\?php\s+print\s+\$item\[/',
            $this->template,
            'AI-807: designer regex stub MUST pass against RAW source (no comment-stripping) -- if this fails, source prose still mentions the legacy raw-php-print-of-item literal somewhere; rephrase as words.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  AI-808 empty-state pattern (AI-780a mirror)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai808_empty_state_block_renders_for_admin_when_data_empty(): void
    {
        $this->assertStringContainsString(
            '@if (empty($data))',
            $this->executable,
            'AI-808: Page template MUST gate empty state on empty($data).'
        );
        $this->assertStringContainsString(
            '@if (is_admin())',
            $this->executable,
            'AI-808: Page template empty-state MUST be wrapped in is_admin() so it only surfaces to editors (AI-104 lineage).'
        );
        $this->assertStringContainsString(
            'mw-canvas-empty-state',
            $this->executable,
            'AI-808: Page template MUST carry the .mw-canvas-empty-state wrapper (AI-780a shape).'
        );
        $this->assertStringContainsString(
            'data-mw-content-type="page"',
            $this->executable,
            'AI-808: Page empty-state MUST carry data-mw-content-type="page" -- explicit, no $params[type] inference needed.'
        );
    }

    #[Test]
    public function ai808_empty_state_carries_page_specific_strings_and_cta(): void
    {
        $this->assertStringContainsString(
            "__('No pages yet')",
            $this->executable,
            "AI-808: Page empty-state heading MUST be __('No pages yet')."
        );
        $this->assertStringContainsString(
            "__('Add your first page to fill this module.')",
            $this->executable,
            "AI-808: Page empty-state body MUST mirror the AI-780a Posts wording -- 'Add your first page to fill this module.'."
        );
        $this->assertStringContainsString(
            "__('+ Add page')",
            $this->executable,
            "AI-808: Page empty-state CTA label MUST be __('+ Add page')."
        );
        $this->assertStringContainsString(
            // task-2026-05-18-561d00 — admin_url caused 404 after Filament route
            // reorganisation; CTA now uses route('filament.admin.resources.pages.create').
            "route('filament.admin.resources.pages.create')",
            $this->executable,
            "AI-808: Page empty-state CTA href MUST route to filament.admin.resources.pages.create."
        );
        // aria-label on the CTA anchor for screen-reader users (the
        // visible "+ Add page" text would announce the "+" literally
        // without the aria-label).
        $this->assertMatchesRegularExpression(
            "/aria-label=\"\\{\\{\\s*__\\('\\+ Add page'\\)\\s*\\}\\}\"/",
            $this->executable,
            "AI-808: Page empty-state CTA MUST carry aria-label='+ Add page' for AT users (mirrors AI-780a accessibility shape)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C-bis  AI-808 CSS source guards (two-stage CSS shipping pattern
    // per SUMMARY.md: pair the source-template test with a source-CSS test
    // so a future agent who deletes the .mw-canvas-empty-state rules from
    // default.css fails this test loudly instead of silently rendering an
    // unstyled empty state). Designer's claim: "CSS already serves all
    // module surfaces via the existing .mw-canvas-empty-state rules
    // (AI-771 cross-package @import)." -- verified here.
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai808_canvas_empty_state_css_rules_exist_in_frontend_assets_source(): void
    {
        // Source of truth: packages/frontend-assets/resources/assets/css
        // /microweber/css/default.css (AI-771 cross-package @import
        // architecture -- consumed by BOTH the Vite bundle AND the
        // Webpack theme bundle from one source).
        $defaultCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/microweber/css/default.css'
        ));

        // Base wrapper class -- the rule body MUST exist (not just the
        // selector mention in a comment). Match `.mw-canvas-empty-state`
        // at line-start (CSS rule, not selector-in-comment-context) AND
        // followed by `{` for the rule-body open.
        $this->assertMatchesRegularExpression(
            '/^\.mw-canvas-empty-state\s*\{/m',
            $defaultCss,
            'AI-808: .mw-canvas-empty-state base rule body MUST exist in default.css (the AI-771 cross-package source of truth).'
        );
        $this->assertMatchesRegularExpression(
            '/^\.mw-canvas-empty-state__title\s*\{/m',
            $defaultCss,
            'AI-808: .mw-canvas-empty-state__title BEM child MUST exist in default.css.'
        );
        $this->assertMatchesRegularExpression(
            '/^\.mw-canvas-empty-state__body\s*\{/m',
            $defaultCss,
            'AI-808: .mw-canvas-empty-state__body BEM child MUST exist in default.css.'
        );
        $this->assertMatchesRegularExpression(
            '/^\.mw-canvas-empty-state__cta\s*\{/m',
            $defaultCss,
            'AI-808: .mw-canvas-empty-state__cta BEM child MUST exist in default.css.'
        );
    }

    #[Test]
    public function ai808_canvas_empty_state_cta_meets_wcag_44px_touch_floor(): void
    {
        // The CTA is a clickable anchor; per the project-wide
        // touch-target floor (44x44px per WCAG 2.5.5; SUMMARY.md
        // Decisions block + multiple AI-516..AI-535 ship references),
        // the .mw-canvas-empty-state__cta MUST declare min-height: 44px.
        // Source-level guard for the rule body.
        $defaultCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/microweber/css/default.css'
        ));

        // Slice the .mw-canvas-empty-state__cta rule body (between
        // the opening `{` after the selector and the next `}`).
        if (preg_match('/^\.mw-canvas-empty-state__cta\s*\{([^}]+)\}/m', $defaultCss, $m)) {
            $ctaBody = $m[1];
            $this->assertMatchesRegularExpression(
                '/min-height:\s*44px/',
                $ctaBody,
                'AI-808: .mw-canvas-empty-state__cta MUST declare min-height: 44px (WCAG 2.5.5 touch-target floor; same rule applied across AI-516..AI-535).'
            );
        } else {
            $this->fail('AI-808: could not slice .mw-canvas-empty-state__cta rule body for WCAG check.');
        }
    }

    #[Test]
    public function ai808_canvas_empty_state_carries_dark_theme_variant(): void
    {
        // Empty-state surface MUST resolve in dark theme too (the
        // canvas iframe + admin chrome both support dark theme).
        // Source-level guard for the `html.dark .mw-canvas-empty-state*`
        // rule existence.
        $defaultCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/microweber/css/default.css'
        ));

        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.mw-canvas-empty-state\s*\{/',
            $defaultCss,
            'AI-808: dark-theme variant of .mw-canvas-empty-state MUST exist (Stage-2 cascade-loss prevention; matches the AI-786 CHANGE pattern).'
        );
    }

    #[Test]
    public function ai808_ai771_cross_package_architecture_documented(): void
    {
        // Designer's claim from the AI-808 email body: "CSS already
        // serves all module surfaces via the existing .mw-canvas-
        // empty-state rules (AI-771 cross-package @import)."
        // Verify the cross-package architecture is documented at
        // the second consumer-side (microweber-filament-theme's
        // general-styles.css) so future agents inheriting this
        // surface understand WHERE the rules actually live and
        // WHY they're not duplicated in two places.
        $themeCss = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->assertStringContainsString(
            'mw-canvas-empty-state',
            $themeCss,
            'AI-808: theme-side general-styles.css MUST document the .mw-canvas-empty-state cross-package architecture (AI-771 lineage).'
        );
        // Also verify the comment cites AI-780 (the source of the
        // pattern) + the source-of-truth path (default.css).
        $this->assertStringContainsString(
            'frontend-assets',
            $themeCss,
            'AI-808: theme-side comment MUST cite frontend-assets as the canonical source location.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  positive structural guards (the new shape is intact)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function pagination_footer_preserved(): void
    {
        $this->assertStringContainsString(
            "@if (isset(\$pages_count) && \$pages_count > 1 && isset(\$paging_param))",
            $this->executable,
            'Pagination footer guard MUST remain (mirrors AI-62 lineage).'
        );
        $this->assertStringContainsString(
            'paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}")',
            $this->executable,
            'Pagination footer MUST emit paging() with num/paging_param/current_page.'
        );
    }

    #[Test]
    public function thumbnail_uses_responsive_helper(): void
    {
        $this->assertStringContainsString(
            'responsive_thumbnail($item[\'image\'], 535, 285',
            $this->executable,
            'Page template MUST use responsive_thumbnail() helper (TICKET-CX lineage, same shape as Posts).'
        );
    }

    #[Test]
    public function description_strips_html_before_limit(): void
    {
        // strip_tags() before Str::limit() so any HTML residue from
        // content_body cannot leak rich markup into the lists view.
        $this->assertMatchesRegularExpression(
            '/Str::limit\(strip_tags\(\(string\)\s*\(\$item\[[\'"]description[\'"]\]\s*\?\?\s*[\'"][\'"]\)\)/',
            $this->executable,
            'Description MUST be strip_tags()-ed before Str::limit() -- defence-in-depth against rich HTML leaking into Page lists.'
        );
    }

    #[Test]
    public function show_fields_gates_are_preserved_for_back_compat(): void
    {
        $expectedFields = ['thumbnail', 'title', 'created_at', 'description', 'read_more'];
        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                "in_array('{$field}', \$show_fields)",
                $this->executable,
                "show_fields gate for '{$field}' MUST be preserved (back-compat with admin Hide-Field toggles)."
            );
        }
    }

    #[Test]
    public function columns_helper_logic_preserved(): void
    {
        $this->assertStringContainsString(
            "get_option('columns', \$params['id'])",
            $this->executable,
            'Columns helper logic MUST read get_option("columns", id) -- back-compat with admin column-count config.'
        );
        $this->assertStringContainsString(
            "'col-md-6 col-lg-4'",
            $this->executable,
            'Default column class MUST be col-md-6 col-lg-4 (3-column responsive layout).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  task-id markers + lineage citations
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ticket_markers_present(): void
    {
        // The docblock at top must cite all 3 ticket IDs since this
        // single rewrite addresses all of them.
        $this->assertStringContainsString('task-2026-05-17-aeb113', $this->template);
        $this->assertStringContainsString('AI-806', $this->template);
        $this->assertStringContainsString('AI-807', $this->template);
        $this->assertStringContainsString('AI-808', $this->template);
    }

    #[Test]
    public function ai780a_ai801_lineage_cited(): void
    {
        // The empty-state implementation explicitly cites the
        // AI-780a/AI-801 pattern lineage so future audits can grep
        // the family.
        $this->assertMatchesRegularExpression(
            '/AI-780a|AI-801/',
            $this->template,
            'Page empty-state MUST cite AI-780a/AI-801 lineage in source-side comment for audit grep.'
        );
    }
}
