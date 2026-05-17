<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-d00884 / AI-809 -- Menu module $menu_name XSS
 * escape pass.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-809
 *
 * Pre-fix: 6 Menu templates interpolated $menu_name raw inside
 * lnotif() empty-state output. Designer enumerated 4
 * (default / simple / small / skin-1); recon during this ship found
 * 2 more (navbar + images) for a total of 6.
 *
 * An admin who renamed their menu to a string like
 *   "><img src=x onerror=alert(1)>
 * would inject that script into the empty-state notification
 * surface, which a second admin's live-edit session would render.
 *
 * Severity Low because lnotif() is editmode-gated -- the empty-
 * state surface only renders inside an admin live-edit session
 * (`src/MicroweberPackages/App/functions/other.php:355` returns
 * early when not in editmode). No frontend leak. Admin-to-admin
 * multi-operator defense-in-depth fix.
 *
 * Fix: wrap $menu_name in Laravel's e() helper (htmlspecialchars
 * with sensible defaults) before concatenating into the lnotif
 * HTML string. Same recipe across all 6 files.
 *
 * Filed under the legacy-Blade audit class lesson
 * (LESSONS.md commit ca66bde9f7) -- same family as AI-806/807
 * Page-template defects.
 */
class MenuD00884AI809MenuNameEscapeContractTest extends TestCase
{
    /**
     * All 6 Menu templates that pre-fix carried the unescaped
     * $menu_name interpolation. 4 from designer's enumeration +
     * 2 surfaced during ship-time recon (navbar + images).
     */
    public static function allMenuTemplatesProvider(): array
    {
        return [
            'default' => ['Modules/Menu/resources/views/templates/default.blade.php'],
            'images'  => ['Modules/Menu/resources/views/templates/images.blade.php'],
            'small'   => ['Modules/Menu/resources/views/templates/small.blade.php'],
            'simple'  => ['Modules/Menu/resources/views/templates/simple.blade.php'],
            'skin-1'  => ['Modules/Menu/resources/views/templates/skin-1.blade.php'],
            'navbar'  => ['Modules/Menu/resources/views/templates/navbar.blade.php'],
        ];
    }

    private function templateContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  positive guards -- $menu_name is wrapped in e() now
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allMenuTemplatesProvider')]
    public function menu_name_is_wrapped_in_e_helper(string $relativePath): void
    {
        $src = $this->templateContents($relativePath);

        // Must contain `e($menu_name)` (htmlspecialchars wrapper).
        // Whitespace-tolerant regex.
        $this->assertMatchesRegularExpression(
            '/e\(\s*\$menu_name\s*\)/',
            $src,
            "AI-809: {$relativePath} MUST wrap \$menu_name in e() helper before injecting into lnotif() HTML."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  negative regression guards -- legacy raw interpolation gone
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allMenuTemplatesProvider')]
    public function legacy_raw_menu_name_interpolation_is_gone(string $relativePath): void
    {
        $src = $this->templateContents($relativePath);

        // Strip JS-style + Blade-style + PHP block comments so the
        // docblock prose describing the pre-fix pattern (e.g.
        // "Pre-fix raw interpolation let an admin who renamed a menu
        // to ...") does not false-fail the absence assertion.
        // Selector-self-match guard family (now 17+ recurrences this
        // session).
        $stripped = preg_replace('~//.*$~m', '', $src);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $stripped);

        // The exact pre-fix shape: $menu_name appearing immediately
        // after a string concatenation (not wrapped in e() or any
        // other escaper). Pin: $menu_name preceded by `. ` (string
        // concat) and NOT preceded by `e(`.
        $this->assertDoesNotMatchRegularExpression(
            '/(?<!e\()\.\s*\$menu_name\s*\./',
            $stripped,
            "AI-809: {$relativePath} MUST NOT carry any raw `. \$menu_name .` interpolation outside an e() wrapper (post-comment-strip)."
        );

        // Belt-and-braces: every occurrence of $menu_name in the
        // executable (post-comment-strip) source MUST be immediately
        // inside an e() call -- count occurrences of bare $menu_name
        // vs occurrences of e($menu_name) and assert they match.
        // (Group A already pins positive presence of e($menu_name);
        // this confirms there's no SECOND occurrence elsewhere that
        // sneaks through unwrapped.)
        $totalMenuName = preg_match_all('/\$menu_name\b/', $stripped);
        $wrappedMenuName = preg_match_all('/e\(\s*\$menu_name\s*\)/', $stripped);
        $this->assertSame(
            $totalMenuName,
            $wrappedMenuName,
            "AI-809: {$relativePath} -- EVERY occurrence of \$menu_name in executable source MUST be wrapped in e(). Got {$totalMenuName} total, {$wrappedMenuName} wrapped."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  task-id markers (audit grep contract)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allMenuTemplatesProvider')]
    public function task_id_marker_present(string $relativePath): void
    {
        $src = $this->templateContents($relativePath);
        $this->assertStringContainsString(
            'task-2026-05-17-d00884',
            $src,
            "AI-809: {$relativePath} MUST carry the task-id marker for audit grep."
        );
        $this->assertStringContainsString(
            'AI-809',
            $src,
            "AI-809: {$relativePath} MUST cite the ticket ID for audit grep."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  recon-surface guards -- ensure no NEW menu template can
    // sneak in with the legacy shape without being added to this test
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function no_other_menu_template_carries_raw_menu_name_interpolation(): void
    {
        // Scan ALL files under Modules/Menu/resources/views/templates/
        // for any *.blade.php that contains $menu_name but is NOT in
        // the data provider list. If a future agent adds a new menu
        // template skin with the legacy raw-interpolation shape, this
        // test fails loudly so we can either add it to the fix list
        // or confirm it uses the escaped shape.
        $templatesDir = base_path('Modules/Menu/resources/views/templates');
        $allBlades = glob($templatesDir . '/*.blade.php');
        $covered = array_map(
            fn ($p) => base_path($p[0]),
            array_values(static::allMenuTemplatesProvider())
        );

        $uncovered = array_diff($allBlades, $covered);

        foreach ($uncovered as $file) {
            $src = (string) file_get_contents($file);
            // If this uncovered template DOES interpolate $menu_name
            // (i.e. would have hit the legacy defect family), fail.
            // If it doesn't reference $menu_name at all, that's fine.
            if (! str_contains($src, '$menu_name')) {
                continue;
            }
            // Strip comments first per selector-self-match guard.
            $stripped = preg_replace('~//.*$~m', '', $src);
            $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
            $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $stripped);

            if (preg_match('/(?<!e\()\.\s*\$menu_name\s*\./', $stripped)) {
                $this->fail(sprintf(
                    'AI-809: uncovered Menu template %s carries raw `. $menu_name .` interpolation. ' .
                    'Add it to MenuD00884AI809MenuNameEscapeContractTest::allMenuTemplatesProvider() ' .
                    'AND apply the e() escape fix.',
                    basename($file)
                ));
            }
        }

        // Sanity-pass when no uncovered templates found.
        $this->addToAssertionCount(1);
    }
}
