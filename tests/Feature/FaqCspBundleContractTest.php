<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-91 / AI-79 / TICKET-AI — FAQ CSS extract regression coverage.
 *
 * Pins:
 *   - `Modules/Faq/resources/views/templates/default.blade.php` no
 *     longer carries any inline `<style>` block — instead it loads a
 *     shared `modules/faq/css/faq-skin.css` via a single
 *     `@once <link rel="stylesheet">` block (same convention as
 *     cycle-89's Post-list bundle).
 *   - The stylesheet exists at the source asset path
 *     `Modules/Faq/resources/assets/css/faq-skin.css` (the public
 *     mirror is gitignored on purpose — `php artisan module:publish`
 *     copies it on deploy).
 *   - The stylesheet replaces the three hardcoded colour literals
 *     (#1157c1, #6f6f6f, #efefef) with theme tokens via `var(...)`
 *     so dark themes don't render a white-on-white card.
 *
 * Style after the cycle-52..90 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class FaqCspBundleContractTest extends TestCase
{
    private const FAQ_BLADE = 'Modules/Faq/resources/views/templates/default.blade.php';
    private const FAQ_CSS   = 'Modules/Faq/resources/assets/css/faq-skin.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function faq_blade_drops_inline_style_block(): void
    {
        // The pre-fix file had a 30-line `<style>...</style>` block
        // at the bottom that violated `style-src 'self'`. Strip Blade
        // {{-- ... --}} comments first so the audit-trail comment
        // doesn't trigger a false positive.
        $src = $this->read(self::FAQ_BLADE);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        $this->assertDoesNotMatchRegularExpression(
            '/<style\\b/',
            $stripped,
            self::FAQ_BLADE . ': must NOT contain any inline <style> block (CSP-violation)'
        );
    }

    #[Test]
    public function faq_blade_loads_skin_css_via_once_block(): void
    {
        // Mirrors cycle-89's pattern for Post skins: `@once` so
        // multiple FAQ modules on one page dedupe the load to a
        // single <link>.
        $src = $this->read(self::FAQ_BLADE);

        $this->assertStringContainsString(
            '@once',
            $src,
            self::FAQ_BLADE . ': must wrap the <link> in @once'
        );
        $this->assertStringContainsString(
            "<link rel=\"stylesheet\" href=\"{{ asset('modules/faq/css/faq-skin.css') }}\">",
            $src,
            self::FAQ_BLADE . ': must emit the canonical <link> to faq-skin.css'
        );
        $this->assertStringContainsString(
            '@endonce',
            $src,
            self::FAQ_BLADE . ': must close the @once with @endonce'
        );
    }

    #[Test]
    public function faq_skin_css_exists_with_audit_trail(): void
    {
        $src = $this->read(self::FAQ_CSS);

        $this->assertStringContainsString(
            'AI-79 / TICKET-AI (cycle-91',
            $src,
            self::FAQ_CSS . ': must carry the AI-79 audit-trail header'
        );
    }

    #[Test]
    public function faq_skin_css_uses_theme_tokens_not_hardcoded_hex(): void
    {
        // The AI-79 brief calls out "lift inline style to stylesheet
        // + theme tokens" — pin that the three rules that previously
        // hardcoded hex now reference CSS custom properties.
        $src = $this->read(self::FAQ_CSS);

        $this->assertMatchesRegularExpression(
            '/color:\\s*var\\(--mw-primary-color\\b/',
            $src,
            'faq-skin.css: question summary colour must resolve via --mw-primary-color'
        );
        $this->assertMatchesRegularExpression(
            '/color:\\s*var\\(--bs-secondary\\b/',
            $src,
            'faq-skin.css: answer body colour must resolve via --bs-secondary'
        );
        $this->assertMatchesRegularExpression(
            '/border:\\s*1px solid var\\(--bs-border-color\\b/',
            $src,
            'faq-skin.css: item border must resolve via --bs-border-color'
        );
    }
}
