<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-08-wordbreak — mobile hardening from the module mobile audit.
 *
 * A long UNBROKEN string in module text (a category name, product/post title,
 * or a pasted URL) forced horizontal page overflow at narrow viewports because
 * it could not wrap at a space. `overflow-wrap: break-word` breaks such a word
 * only when it would otherwise overflow. The rule lives in the PUBLIC frontend
 * master (default.css) — NOT ui.css, which feeds the admin bundle.
 */
class FrontendWordbreakModuleTextContractTest extends TestCase
{
    private const SOURCE = 'packages/frontend-assets/resources/assets/css/microweber/css/default.css';
    private const BUNDLE = 'public/vendor/microweber-packages/frontend-assets/build/default.css';

    private function assertHasRule(string $relative): void
    {
        $css = (string) file_get_contents(base_path($relative));
        $this->assertMatchesRegularExpression(
            '/\.module-categories\s+a[^{]*\{[^}]*overflow-wrap:\s*break-word/s',
            $css,
            "{$relative} must carry the overflow-wrap:break-word rule on .module-categories a (long-name overflow guard)."
        );
    }

    #[Test]
    public function source_has_module_text_wordbreak_rule(): void
    {
        $this->assertHasRule(self::SOURCE);
    }

    #[Test]
    public function served_public_bundle_has_the_wordbreak_rule(): void
    {
        // Tier-2 served-bundle guard — the public default.css must carry the
        // rebuilt rule, not a stale bundle.
        $this->assertHasRule(self::BUNDLE);
    }

}
