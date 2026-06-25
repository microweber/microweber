<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-99..101 / AI-100 + AI-102 + AI-104 — Big2 mobile audit
 * regression coverage (3 of 5 tickets; AI-101 + AI-103 are
 * content-seed issues out of code-fix scope).
 *
 * Pins:
 *   - AI-100 (Big2 hero background not rendering on mobile):
 *     `mw-header-section-mh-100vh` lives on the outer `<section>`
 *     not the inner `.mw-layout-container`, so the absolutely-
 *     positioned `<module type="background">` block has a real
 *     containing-block height to anchor against.
 *   - AI-102 (hamburger toggle is a real `<button>`, not a `<span>`):
 *     `packages/frontend-assets/resources/assets/widgets/hamburger.js`
 *     creates a `<button type="button" aria-label aria-expanded>`
 *     and `mobileMenu()` keeps `aria-expanded` in sync with the
 *     menu-open state.
 *   - AI-104 (empty-content placeholder doesn't leak to anon
 *     visitors): every Content list-skin Blade with the
 *     "No content added…" placeholder wraps it in `@if(is_admin())`.
 *
 * Style after the cycle-52..98 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Big2MobileAuditContractTest extends TestCase
{
    private const HEADER_SKIN_1   = 'Templates/Big2/resources/views/modules/layouts/templates/header/skin-1.blade.php';
    private const HAMBURGER_JS    = 'packages/frontend-assets/resources/assets/widgets/hamburger.js';
    private const CONTENT_PLACEHOLDER_FILES = [
        'Modules/Content/resources/views/templates/default.blade.php',
        'Modules/Content/resources/views/templates/skin-1.blade.php',
        'Modules/Content/resources/views/templates/dictionary.blade.php',
        'Modules/Content/resources/views/templates/masonry.blade.php',
        'Modules/Content/resources/views/templates/sidebar.blade.php',
        'Modules/Content/resources/views/templates/search.blade.php',
    ];

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ai_100_hero_section_carries_min_height_class_on_outer_section(): void
    {
        // Big2 is a separate gitignored package; skip when not
        // present (same pattern as Big2A11ySweepContractTest).
        if (!is_dir(base_path('Templates/Big2/resources/views'))) {
            $this->markTestSkipped('Templates/Big2/ not present (separate package)');
        }

        $src = $this->read(self::HEADER_SKIN_1);

        // Positive: the outer `<section ... class="...">` declares the
        // mw-header-section-mh-100vh class.
        $this->assertMatchesRegularExpression(
            '/<section\\s[^>]*class="[^"]*\\bmw-header-section-mh-100vh\\b/',
            $src,
            self::HEADER_SKIN_1 . ': outer <section> must carry `mw-header-section-mh-100vh` so the absolutely-positioned <module type="background"> block has a real containing-block height'
        );

        // Negative: the inner `.mw-layout-container` div must NOT carry
        // the class anymore (was the pre-fix location).
        $this->assertDoesNotMatchRegularExpression(
            '/<div\\s[^>]*class="[^"]*\\bmw-layout-container\\b[^"]*\\bmw-header-section-mh-100vh\\b/',
            $src,
            self::HEADER_SKIN_1 . ': inner `.mw-layout-container` must NOT carry `mw-header-section-mh-100vh` anymore (was the pre-fix shape)'
        );
    }

    #[Test]
    public function ai_102_hamburger_is_real_button_with_aria(): void
    {
        $src = $this->read(self::HAMBURGER_JS);

        // Positive: createElement("button") with type="button".
        $this->assertStringContainsString(
            'document.createElement("button")',
            $src,
            self::HAMBURGER_JS . ': hamburger toggle must be created as `<button>`, not `<span>`'
        );
        $this->assertStringContainsString(
            'mobileMenu.type = "button"',
            $src,
            self::HAMBURGER_JS . ': must declare `type="button"` so it does not submit forms when nested in one'
        );

        // Positive: aria-label localized via mw.lang fallback.
        $this->assertMatchesRegularExpression(
            '/setAttribute\\(\\s*"aria-label",\\s*\\(window\\.mw && window\\.mw\\.lang\\)\\s*\\?\\s*window\\.mw\\.lang\\("Toggle navigation"\\)/',
            $src,
            self::HAMBURGER_JS . ': must set aria-label via mw.lang("Toggle navigation") with literal fallback'
        );

        // Positive: aria-expanded starts at "false".
        $this->assertStringContainsString(
            'mobileMenu.setAttribute("aria-expanded", "false")',
            $src,
            self::HAMBURGER_JS . ': must initialize aria-expanded to "false"'
        );

        // mobileMenu() must keep aria-expanded in sync.
        $this->assertMatchesRegularExpression(
            '/document\\.body\\.classList\\.contains\\("mw-vhmbgr-menu-active"\\)[\\s\\S]{0,400}setAttribute\\("aria-expanded",\\s*isOpen\\s*\\?\\s*"true"\\s*:\\s*"false"\\)/',
            $src,
            self::HAMBURGER_JS . ': mobileMenu() must keep aria-expanded synced with the body.mw-vhmbgr-menu-active state'
        );

        // Negative: the legacy `<span>` shape must be gone.
        $stripped = preg_replace('!//.*$!m', '', $src);
        $stripped = preg_replace('!/\\*[\\s\\S]*?\\*/!', '', $stripped);
        $this->assertDoesNotMatchRegularExpression(
            '/var mobileMenu = document\\.createElement\\("span"\\);\\s*mobileMenu\\.className = "mw-vhmbgr-wrapper"/',
            $stripped,
            self::HAMBURGER_JS . ': legacy `createElement("span")` shape for the hamburger toggle must be gone'
        );
    }

    // task-2026-06-07-pmprod superseded AI-104: the per-template inline
    // `<p class="mw-pictures-clean">No content added…` placeholder was
    // centralised into the shared partial
    // `modules.content::partials.module-empty-state`, whose markup is
    // resolved by \Modules\Content\Services\ContentModuleEmptyState and
    // wrapped in `@if(is_admin())`. The six list templates now `@include`
    // that partial inside their empty branch instead of carrying the
    // bare <p>. So the contract is now: (a) each template's empty branch
    // includes the shared partial, and (b) the shared partial is the
    // single `is_admin()`-guarded source of the placeholder.
    private const EMPTY_STATE_PARTIAL = 'Modules/Content/resources/views/partials/module-empty-state.blade.php';

    #[Test]
    public function ai_104_empty_content_placeholder_is_admin_only(): void
    {
        // (a) Each list-skin must delegate its empty state to the shared
        // partial (no more copy-pasted inline placeholder markup).
        foreach (self::CONTENT_PLACEHOLDER_FILES as $rel) {
            $src = $this->read($rel);
            $this->assertStringContainsString(
                "@include('modules.content::partials.module-empty-state'",
                $src,
                "{$rel}: empty-content placeholder must delegate to the shared `module-empty-state` partial (task-2026-06-07-pmprod)"
            );

            // Negative: the legacy inline bare placeholder must be gone.
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $this->assertStringNotContainsString(
                '<p class="mw-pictures-clean">No content added',
                $stripped,
                "{$rel}: legacy inline `<p class=\"mw-pictures-clean\">No content added` placeholder must be gone (centralised into the shared partial)"
            );
        }

        // (b) The shared partial is the single is_admin()-guarded source
        // of the empty-state markup, so it never leaks to anon visitors.
        $partial = $this->read(self::EMPTY_STATE_PARTIAL);
        $partialStripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $partial);
        $this->assertMatchesRegularExpression(
            '/@if\\s*\\(\\s*is_admin\\(\\s*\\)\\s*\\)/',
            $partialStripped,
            self::EMPTY_STATE_PARTIAL . ': shared empty-state partial must wrap its markup in `@if(is_admin())` so it never leaks onto anonymous public pages (AI-104)'
        );
    }
}
