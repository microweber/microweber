<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-4f4f83 / AI-852 + task-2026-05-17-bdba1a / AI-853 bundled.
 * Jira: https://microweber.atlassian.net/browse/AI-852
 *       https://microweber.atlassian.net/browse/AI-853
 *
 * Round 17 deeper-Menu audit shipped 3 defects + 1 broken affordance
 * across 6 Menu skins in a single bundled diff:
 *
 *   Defect 1 (AI-852 / textContent-leak family):
 *     skin support <style>+<script> blocks shipped UNCONDITIONALLY,
 *     outside the @if(menu has items) gate. When menu was empty, ~600
 *     bytes of CSS+JS source became text-node descendants of the module
 *     wrapper -- visible to Lighthouse, some AT readers, and Google's
 *     mobile-first crawler as part of the menu's "content".
 *
 *   Defect 2 (AI-852 / semantic-mismatch family, sibling of AI-816):
 *     empty-state used `mw-notification mw-success` chrome -- "success"
 *     semantic on a "nothing yet" state. Same defect class as AI-816's
 *     ->color('success') on unsubmitted save CTAs.
 *
 *   Defect 3 (AI-852 / machine-slug leak):
 *     empty-state displayed raw $menu_name slug (e.g. "header_menu").
 *     Internal naming leaked to admin surface.
 *
 *   Affordance (AI-853):
 *     class `mw-open-module-settings` on the empty-state suggested a
 *     click-to-edit affordance but no handler was wired in the current
 *     bundle. Promise without delivery -- sibling to AI-848 logo-render
 *     gap and AI-851 double-redirect notice (3-instance threshold for
 *     codification as a sub-family).
 *
 * Single-diff fix shape per affected skin:
 *
 *   - skin support style/script moves INSIDE the truthy @if branch
 *     (Defect 1 closed)
 *   - empty-state chrome migrates from `mw-notification mw-success` to
 *     canonical `.mw-canvas-empty-state` per AI-780a/AI-808/AI-815
 *     lineage (Defect 2 closed)
 *   - raw $menu_name slug replaced with action-focused copy + real
 *     `<a class="mw-canvas-empty-state__cta" href="...settings/menus">`
 *     CTA (Defect 3 closed)
 *   - the new __cta anchor IS the click-affordance AI-853 wanted -- a
 *     native <a href> that browser-navigates to the menu admin.
 *     mw-open-module-settings broken JS hook deleted entirely (AI-853
 *     closed)
 *   - is_admin() gate wraps the empty-state so anonymous frontend
 *     visitors never see editor copy
 *
 * Supersedes task-2026-05-17-d00884 / AI-809 in the 6 templates
 * (AI-852 removes $menu_name interpolation entirely -- strictly
 * stronger than e($menu_name) wrap). AI-809 contract test pin-evolved
 * to assert conditional: "if $menu_name is interpolated, MUST be e()-
 * wrapped". Linktree (AI-842) keeps the legacy lnotif shape.
 *
 * Scope: 6 Menu templates per AI-809 enumeration -- navbar / default /
 * simple / small / skin-1 / images. Linktree (AI-842) out-of-scope
 * for this bundle (different shape, designer Round 13.3 dispatch
 * recommended consistency-with-AI-809-lnotif at that time; can be
 * follow-up AI-852a if cross-skin AI-852 consistency is later
 * preferred).
 */
class Menu4f4f83AI852AI853EmptyStateBundleContractTest extends TestCase
{
    /**
     * The 6 Menu templates in scope for the AI-852 + AI-853 bundle.
     *
     * @return array<string, array{0: string}>
     */
    public static function bundleTemplateProvider(): array
    {
        return [
            'navbar.blade.php'  => ['Modules/Menu/resources/views/templates/navbar.blade.php'],
            'default.blade.php' => ['Modules/Menu/resources/views/templates/default.blade.php'],
            'simple.blade.php'  => ['Modules/Menu/resources/views/templates/simple.blade.php'],
            'small.blade.php'   => ['Modules/Menu/resources/views/templates/small.blade.php'],
            'skin-1.blade.php'  => ['Modules/Menu/resources/views/templates/skin-1.blade.php'],
            'images.blade.php'  => ['Modules/Menu/resources/views/templates/images.blade.php'],
        ];
    }

    /**
     * Templates that ship inline <style> or <script> blocks tied to
     * the rendered menu (i.e. skin support code that should ride with
     * the menu HTML, not the empty-state branch). Pinned by Defect 1
     * assertions only.
     *
     * @return array<string, array{0: string}>
     */
    public static function skinSupportTemplateProvider(): array
    {
        return [
            'navbar.blade.php'  => ['Modules/Menu/resources/views/templates/navbar.blade.php'],
            'default.blade.php' => ['Modules/Menu/resources/views/templates/default.blade.php'],
            'small.blade.php'   => ['Modules/Menu/resources/views/templates/small.blade.php'],
            'skin-1.blade.php'  => ['Modules/Menu/resources/views/templates/skin-1.blade.php'],
            'images.blade.php'  => ['Modules/Menu/resources/views/templates/images.blade.php'],
        ];
    }

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AI-852 / AI-853 canonical empty-state chrome present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('bundleTemplateProvider')]
    public function canonical_empty_state_chrome_present(string $relativePath): void
    {
        $source = $this->read($relativePath);

        $this->assertStringContainsString(
            'mw-canvas-empty-state',
            $source,
            "AI-852: {$relativePath} MUST render .mw-canvas-empty-state chrome per AI-780a/AI-808/AI-815 lineage (Defect 2: replaces semantic-mismatched mw-notification mw-success)."
        );
        $this->assertStringContainsString(
            'data-mw-content-type="menu"',
            $source,
            "AI-852: {$relativePath} MUST carry data-mw-content-type=\"menu\" anchor per AI-780a canonical empty-state attribute contract."
        );
    }

    #[Test]
    #[DataProvider('bundleTemplateProvider')]
    public function cta_anchor_present_with_settings_menus_href(string $relativePath): void
    {
        $source = $this->read($relativePath);

        // The new __cta anchor IS the click-affordance AI-853 wanted.
        $this->assertStringContainsString(
            'mw-canvas-empty-state__cta',
            $source,
            "AI-853: {$relativePath} MUST carry .mw-canvas-empty-state__cta anchor (real <a href>) as the click-affordance, replacing the broken mw-open-module-settings JS hook."
        );

        // Href points at admin_url('settings/menus') -- match either
        // Blade double-curly admin_url(...) OR PHP-tag print admin_url(...)
        // closing-PHP-tag shape. (Parser-meaningful-character family
        // applies: this prose must NOT contain the literal closing-PHP-
        // tag character pair `?` + `>` because `//` line comments don't
        // shield it from the tokenizer.)
        $this->assertMatchesRegularExpression(
            "/admin_url\\(\\s*['\"]settings\\/menus['\"]\\s*\\)/",
            $source,
            "AI-853: {$relativePath} MUST link CTA to admin_url('settings/menus') for native browser navigation to the menu admin."
        );
    }

    #[Test]
    #[DataProvider('bundleTemplateProvider')]
    public function empty_state_gated_by_is_admin(string $relativePath): void
    {
        $source = $this->read($relativePath);

        // is_admin() gate keeps the empty-state admin-only so
        // anonymous frontend visitors never see editor copy.
        // Match either Blade @if(is_admin()) or PHP if (is_admin()).
        $this->assertMatchesRegularExpression(
            '/(?:@if\s*\(\s*is_admin\(\)\s*\)|if\s*\(\s*is_admin\(\)\s*\))/',
            $source,
            "AI-852: {$relativePath} MUST gate the empty-state inside is_admin() so anonymous visitors don't see editor copy."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Defect 1: skin support <style>/<script> NOT at top-level
    //           (must be inside the truthy menu branch)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('skinSupportTemplateProvider')]
    public function skin_support_no_longer_ships_at_top_level(string $relativePath): void
    {
        $source = $this->read($relativePath);

        // Top-level shape (pre-fix): <script> or <style> appears as a
        // sibling of the @if/@else block, not nested inside its truthy
        // branch. Post-fix: the AI-852 docblock comment is at top
        // level but the actual <script>/<style> tag bodies are inside
        // the truthy branch.
        //
        // Robust detection: locate the AI-852 task-id marker, then
        // verify the FIRST <script or <style executable tag in the
        // file appears AFTER the truthy-branch opener (skin-support is
        // now downstream of the truthy-branch gate).
        //
        // Selector-self-match guard (18+ session-recurrences): strip
        // Blade {{-- ... --}} + PHP /* ... */ + // comments FIRST so
        // docblock prose mentioning <script>/<style> tokens doesn't
        // false-match. We DO preserve byte-positions by replacing
        // matched comment text with same-length spaces rather than
        // removing it -- byte-offset reasoning needs positions in the
        // ORIGINAL file's coordinate system.
        $stripped = preg_replace_callback('~//[^\n]*~', fn ($m) => str_repeat(' ', strlen($m[0])), $source);
        $stripped = preg_replace_callback('~/\*[\s\S]*?\*/~', fn ($m) => str_repeat(' ', strlen($m[0])), $stripped);
        $stripped = preg_replace_callback('~\{\{--[\s\S]*?--\}\}~', fn ($m) => str_repeat(' ', strlen($m[0])), $stripped);

        // Find the menu_tree truthy-branch opener (either Blade or PHP shape).
        $truthyOpenerPos = false;
        if (preg_match('/@if\s*\(\s*\$mt\s*!=\s*false\s*\)/', $stripped, $m, PREG_OFFSET_CAPTURE)) {
            $truthyOpenerPos = $m[0][1];
        } elseif (preg_match('/if\s*\(\s*\$mt\s*!=\s*false\s*\)\s*\{/', $stripped, $m, PREG_OFFSET_CAPTURE)) {
            $truthyOpenerPos = $m[0][1];
        }

        $this->assertNotFalse(
            $truthyOpenerPos,
            "AI-852: {$relativePath} MUST carry an `\$mt != false` truthy-branch opener (the gate inside which skin support code now lives)."
        );

        // Find the first executable <script ...> or <style ...> opener
        // in the comment-stripped source.
        $tagPos = false;
        if (preg_match('/<(?:script|style)\b/', $stripped, $m, PREG_OFFSET_CAPTURE)) {
            $tagPos = $m[0][1];
        }

        if ($tagPos === false) {
            // No skin-support tag at all -- vacuous pass (simple.blade.php
            // shape; though that's not in this provider).
            $this->addToAssertionCount(1);
            return;
        }

        $this->assertGreaterThan(
            $truthyOpenerPos,
            $tagPos,
            "AI-852 Defect 1: {$relativePath} -- first executable <script>/<style> tag MUST appear AFTER the `\$mt != false` truthy-branch opener (skin support code lives inside the truthy branch so empty-menu renders ship zero CSS/JS bytes)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Defect 2/3: legacy mw-notification mw-success + lnotif
    //           empty-state shapes are gone
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('bundleTemplateProvider')]
    public function legacy_lnotif_empty_state_gone(string $relativePath): void
    {
        $source = $this->read($relativePath);

        // Selector-self-match guard (18+ session-recurrences): strip
        // Blade {{-- ... --}} + PHP /* ... */ + // comments before the
        // negative assertion so docblock prose mentioning the legacy
        // shape doesn't false-fail.
        $stripped = preg_replace('~//.*$~m', '', $source);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/\blnotif\s*\(/',
            $stripped,
            "AI-852: {$relativePath} MUST NOT carry an executable lnotif() empty-state call (the 6 bundled templates migrated to .mw-canvas-empty-state chrome; linktree handled separately under AI-842)."
        );
    }

    #[Test]
    #[DataProvider('bundleTemplateProvider')]
    public function legacy_mw_open_module_settings_hook_gone(string $relativePath): void
    {
        $source = $this->read($relativePath);

        // Selector-self-match guard (18+ session-recurrences).
        $stripped = preg_replace('~//.*$~m', '', $source);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/class\s*=\s*["\'"][^"\']*\bmw-open-module-settings\b/',
            $stripped,
            "AI-853: {$relativePath} MUST NOT carry the broken mw-open-module-settings JS-hook class (replaced by the .mw-canvas-empty-state__cta native <a href> affordance)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task-id markers (AI-852 + AI-853 + AI-809 supersession
    //           citation for cross-surface audit grep)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('bundleTemplateProvider')]
    public function task_id_markers_present(string $relativePath): void
    {
        $source = $this->read($relativePath);

        $this->assertStringContainsString(
            'task-2026-05-17-4f4f83',
            $source,
            "AI-852: {$relativePath} MUST carry the AI-852 task-id marker for cross-surface audit grep."
        );
        $this->assertStringContainsString(
            'AI-852',
            $source,
            "AI-852: {$relativePath} MUST cite the AI-852 ticket ID."
        );
        $this->assertStringContainsString(
            'task-2026-05-17-bdba1a',
            $source,
            "AI-853: {$relativePath} MUST carry the AI-853 task-id marker (bundled ship)."
        );
        $this->assertStringContainsString(
            'AI-853',
            $source,
            "AI-853: {$relativePath} MUST cite the AI-853 ticket ID."
        );
        $this->assertStringContainsString(
            'AI-809',
            $source,
            "AI-852: {$relativePath} MUST cite AI-809 supersession lineage (the bundle removes \$menu_name interpolation entirely -- stronger than the AI-809 e() wrap)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — sub-family codification: "promise without delivery" UX
    //           defect (AI-848 logo gap + AI-851 double-redirect + AI-853
    //           broken affordance = 3-instance threshold per designer
    //           Round 17 closeout).
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_closes_promise_without_delivery_defect(): void
    {
        // Cross-cutting verification: NONE of the 6 templates may
        // retain any class hook that promises behaviour the codebase
        // doesn't deliver. mw-open-module-settings was the canonical
        // example; any *-open-* hook on the empty-state surface is a
        // suspect shape unless it's wired.
        foreach (static::bundleTemplateProvider() as [$relativePath]) {
            $source = $this->read($relativePath);

            // Selector-self-match guard.
            $stripped = preg_replace('~//.*$~m', '', $source);
            $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
            $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $stripped);

            $this->assertDoesNotMatchRegularExpression(
                '/class\s*=\s*["\'"][^"\']*\bmw-open-[a-z-]+\b/',
                $stripped,
                "AI-853 sub-family guard: {$relativePath} MUST NOT carry any unwired mw-open-* hook on the empty-state surface (promise-without-delivery defect family; sibling to AI-848 + AI-851)."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — Scope guard: linktree.blade.php is OUT of scope for the
    //           AI-852 bundle (AI-842 lnotif consistency decision stands).
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function linktree_remains_under_ai842_lnotif_shape(): void
    {
        // AI-852 explicitly scoped to the 6 AI-809-enumerated templates.
        // Linktree's empty-state was added under AI-842 (designer Round
        // 13.3 closeout) with deliberate consistency-with-AI-809-lnotif.
        // If a future agent ports linktree to the AI-852 .mw-canvas-empty-
        // state shape, that would be AI-852a follow-up (cross-skin
        // consistency upgrade) and would update this guard.
        $source = $this->read('Modules/Menu/resources/views/templates/linktree.blade.php');

        $this->assertMatchesRegularExpression(
            '/e\(\s*\$menu_name\s*\)/',
            $source,
            'AI-852 scope guard: linktree.blade.php remains under the AI-842 lnotif+e($menu_name) shape — out of scope for the AI-852 bundle. Port to .mw-canvas-empty-state would be AI-852a follow-up.'
        );
    }
}
