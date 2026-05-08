<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-97 / AI-86 / TICKET-Q — "Go to Live Edit" admin chip scoping
 * regression coverage.
 *
 * Pins:
 *   - Each of the 3 chip render points carries an explicit
 *     `is_admin()` guard, defense-in-depth so a future regression
 *     that includes the partial outside the admin layout can't
 *     leak the chip onto public-site visitors.
 *   - The pre-existing `user_can_access('module.content.edit')`
 *     gates stay in place (auth-level check).
 *   - The pre-AI-86 baseline (cycle-22 + cycle-49 had only the
 *     auth gate) is hardened to AND BOTH together.
 *
 * Style after the cycle-52..96 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class GoLiveEditChipScopingContractTest extends TestCase
{
    private const TOPBAR2 = 'src/MicroweberPackages/Admin/resources/views/layouts/partials/topbar2-links-right-default.blade.php';
    private const TOPBAR  = 'src/MicroweberPackages/Admin/resources/views/layouts/partials/topbar.blade.php';
    private const FILA    = 'src/MicroweberPackages/Admin/resources/views/livewire/filament/top-navigation-go-live-edit.blade.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function topbar2_chip_gates_on_is_admin_and_user_can_access(): void
    {
        $src = $this->read(self::TOPBAR2);

        // Both halves of the gate present in a single @if expression.
        $this->assertMatchesRegularExpression(
            "/@if\\s*\\(\\s*is_admin\\(\\)\\s*&&\\s*user_can_access\\('module\\.content\\.edit'\\)\\s*\\)/",
            $src,
            self::TOPBAR2 . ': must gate the chip on `is_admin() && user_can_access(\'module.content.edit\')`'
        );
    }

    #[Test]
    public function topbar_chip_gates_on_is_admin_and_user_can_access(): void
    {
        $src = $this->read(self::TOPBAR);

        // The PHP-block @if uses bare `<? php if (...) ? >` syntax
        // (spaces inserted in this comment to dodge the PHP parser
        // treating the literal `?` + `>` as a close-tag).
        $this->assertMatchesRegularExpression(
            "/<\\?php\\s+if\\s*\\(\\s*is_admin\\(\\)\\s*&&\\s*user_can_access\\('module\\.content\\.edit'\\)\\s*\\)/",
            $src,
            self::TOPBAR . ': must gate the chip on `is_admin() && user_can_access(\'module.content.edit\')`'
        );

        // The live-edit-href-set anchor must live INSIDE the
        // `is_admin() && user_can_access(...)` gate. Walk forward
        // from the gate and confirm the next chip block matches.
        $this->assertMatchesRegularExpression(
            '/is_admin\\(\\)\\s*&&\\s*user_can_access\\(\'module\\.content\\.edit\'\\)[\\s\\S]{0,800}go-live-edit-href-set-view[\\s\\S]{0,500}go-live-edit-href-set/',
            $src,
            self::TOPBAR . ': both `go-live-edit-href-set` anchors must live inside the `is_admin() && user_can_access(...)` gate'
        );
    }

    #[Test]
    public function filament_chip_gates_on_is_admin(): void
    {
        $src = $this->read(self::FILA);

        $this->assertMatchesRegularExpression(
            "/@if\\s*\\(\\s*is_admin\\(\\)\\s*\\)/",
            $src,
            self::FILA . ': Filament render-hook chip must gate on @if (is_admin())'
        );
        $this->assertStringContainsString(
            '@endif',
            $src,
            self::FILA . ': must close the @if with @endif'
        );

        // The actual <x-filament::button> must live INSIDE the
        // is_admin guard. The button render must not be the very
        // first non-comment line — a leading `@if (is_admin())` must
        // come before it.
        $this->assertMatchesRegularExpression(
            "/@if\\s*\\(\\s*is_admin\\(\\)\\s*\\)[\\s\\S]*?<x-filament::button/",
            $src,
            self::FILA . ': <x-filament::button> must be wrapped inside @if (is_admin())'
        );
    }

    #[Test]
    public function audit_trail_comment_documents_ai_86_provenance(): void
    {
        // Each of the 3 files must carry the AI-86 audit-trail
        // comment so future maintainers know why the guard is there
        // (defense-in-depth) and don't strip it as "redundant".
        foreach ([self::TOPBAR2, self::TOPBAR, self::FILA] as $rel) {
            $src = $this->read($rel);
            $this->assertStringContainsString(
                'AI-86 / TICKET-Q (cycle-97',
                $src,
                "{$rel}: must carry the AI-86 audit-trail comment explaining the defense-in-depth guard"
            );
        }
    }
}
