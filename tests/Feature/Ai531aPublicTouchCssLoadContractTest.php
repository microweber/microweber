<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-531a — public-touch.css load-link presence in every public template.
 *
 * Tester runtime evaluation 2026-05-15 (390×844) discovered that the
 * AI-518a / AI-603 fixes shipped in `public-touch.css` did NOT reach
 * the live site because the active template was Big2, and Big2's
 * `master.blade.php` only loaded `templates/big2/dist/build/app.css` —
 * NOT `public-touch.css`. Only the Bootstrap template's master.blade.php
 * carried the load link.
 *
 * Fix (this commit): add the `public-touch.css` load link to every
 * public template's `master.blade.php`. The path
 * `templates/bootstrap/css/public-touch.css` points at the Bootstrap
 * template asset dir because that's where the file is built (Vite
 * source + served mirror). The Bootstrap template is part of every
 * Microweber install (reference template), so the cross-template path
 * coupling is stable.
 *
 * Long-term follow-up still tracked as AI-531a: migrate
 * `public-touch.css` to a template-agnostic location.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai531aPublicTouchCssLoadContractTest extends TestCase
{
    private const ASSET_PATH = "templates/bootstrap/css/public-touch.css";

    /**
     * Every template under `Templates/` that has a
     * `resources/views/layouts/master.blade.php` must load
     * `public-touch.css`. Fails if a template's master.blade.php
     * exists but does not include the asset.
     */
    #[Test]
    public function every_public_template_master_loads_public_touch_css(): void
    {
        // Iterate templates present in this checkout. Gitignored
        // templates simply won't appear in the glob, so the test
        // gracefully adapts to whichever templates are installed.
        $masters = glob(base_path('Templates/*/resources/views/layouts/master.blade.php'));
        $this->assertNotEmpty($masters, 'At least one public template master.blade.php must exist');

        foreach ($masters as $master) {
            $content = file_get_contents($master);
            $this->assertStringContainsString(
                self::ASSET_PATH,
                $content,
                "AI-531a: {$master} must load `" . self::ASSET_PATH . "` so the public-touch.css 44×44 floor rules reach the live site"
            );
        }
    }

    #[Test]
    public function big2_template_master_loads_public_touch_css(): void
    {
        // Tester-specific anchor: Big2 was the audit-site active
        // template that didn't load the file. Pin it explicitly so a
        // future refactor that removes the AI-531a load link
        // false-fails here with a clear Big2-anchored message.
        //
        // NOTE: `Templates/Big2/` is gitignored in .gitignore line 94,
        // so the edit only persists on machines that have Big2 checked
        // out (audit site + dev installs that pulled it via composer or
        // template installer). Fresh git clones won't have Big2 at all.
        // Skip gracefully when absent rather than false-failing — the
        // long-term centralization fix (AI-531a follow-up) supersedes
        // the need for this per-template patching.
        $path = base_path('Templates/Big2/resources/views/layouts/master.blade.php');
        if (!file_exists($path)) {
            $this->markTestSkipped(
                'Templates/Big2 not present in this checkout (gitignored). '
                . 'Long-term AI-531a fix is centralization to a '
                . 'template-agnostic asset location.'
            );
        }
        $big2 = file_get_contents($path);
        $this->assertStringContainsString(
            self::ASSET_PATH,
            $big2,
            'AI-531a: Big2 template master.blade.php must load public-touch.css (resolves the 2026-05-15 tester runtime gap)'
        );
    }

    #[Test]
    public function bootstrap_template_continues_to_load_public_touch_css(): void
    {
        // Regression guard: don't accidentally remove the original
        // Bootstrap-template load link while editing Big2.
        $bootstrap = file_get_contents(base_path('Templates/Bootstrap/resources/views/layouts/master.blade.php'));
        $this->assertStringContainsString(
            self::ASSET_PATH,
            $bootstrap,
            'Bootstrap template master.blade.php must continue to load public-touch.css (regression guard)'
        );
    }

    #[Test]
    public function ai531a_marker_recorded_in_big2_master(): void
    {
        // Pin the AI-531a docblock so the next agent can trace the
        // cross-template path-coupling decision. Same gitignore-skip
        // semantics as `big2_template_master_loads_public_touch_css`.
        $path = base_path('Templates/Big2/resources/views/layouts/master.blade.php');
        if (!file_exists($path)) {
            $this->markTestSkipped('Templates/Big2 not present (gitignored).');
        }
        $big2 = file_get_contents($path);
        $this->assertStringContainsString('AI-531a', $big2);
        $this->assertStringContainsString('SOUL #56', $big2);
        // Date marker (line-wrapped in the docblock, so check the
        // date itself rather than a multi-word phrase).
        $this->assertStringContainsString('2026-05-15', $big2);
    }
}
