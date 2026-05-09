<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-114 / AI-132 / SEC-01 — Embed module admin-only gate +
 * --seeding=test fixture gate regression coverage.
 *
 * Pins:
 *   - `Modules\Embed\Filament\EmbedModuleSettings::canAccess()`
 *     returns `is_admin()` so non-admin roles cannot reach the
 *     option-set form.
 *   - `Modules\Embed\Microweber\EmbedModule::render()` emits an
 *     audit-log entry for every non-empty embed render (defense-
 *     in-depth — if the canAccess gate is ever bypassed by a
 *     regression, the audit log is the canary).
 *   - `database/seeds/DatabaseSeeder.php` documents the
 *     `SEEDING_MODE=test` env-flag gate. Test fixtures are only
 *     created when the flag is set, so production `db:seed` runs
 *     never accidentally create test admin accounts.
 *
 * Style after the cycle-52..113 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Sec01EmbedAdminGateContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function embed_settings_can_access_returns_is_admin(): void
    {
        $src = $this->read('Modules/Embed/Filament/EmbedModuleSettings.php');

        // The gate must call is_admin() — Filament's panel-level
        // canAccess() short-circuits the route; non-admins can't
        // reach the form to set source_code.
        $this->assertMatchesRegularExpression(
            '/public static function canAccess\\(\\): bool\\s*\\{\\s*return is_admin\\(\\)/',
            $src,
            'EmbedModuleSettings: canAccess() must `return is_admin()`'
        );
    }

    #[Test]
    public function embed_module_render_emits_audit_log(): void
    {
        $src = $this->read('Modules/Embed/Microweber/EmbedModule.php');

        // The audit log is defense-in-depth — even if the canAccess
        // gate is bypassed by a future regression, the log gives
        // an early warning.
        $this->assertStringContainsString(
            "logger()->info('embed.render'",
            $src,
            'EmbedModule::render must call logger()->info(\'embed.render\', ...) for audit trail'
        );

        $this->assertStringContainsString(
            "'is_admin'",
            $src,
            'EmbedModule::render audit log must record the is_admin() state'
        );

        // The logger call must be wrapped in try/catch so a misc-
        // configured log channel can't break the render pipeline.
        $this->assertMatchesRegularExpression(
            '/try\\s*\\{[\\s\\S]{0,500}logger\\(\\)->info\\(\'embed\\.render\'[\\s\\S]{0,500}\\}\\s*catch\\s*\\(\\\\Throwable/',
            $src,
            'EmbedModule::render audit-log call must be wrapped in try/catch (best-effort)'
        );
    }

    #[Test]
    public function database_seeder_gates_test_fixtures_behind_seeding_mode_flag(): void
    {
        $src = $this->read('database/seeds/DatabaseSeeder.php');

        $this->assertStringContainsString(
            "AI-132 / SEC-01",
            $src,
            'DatabaseSeeder must carry the AI-132 audit-trail comment'
        );

        // The flag check pattern.
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*env\\(\\s*'SEEDING_MODE'\\s*\\)\\s*===\\s*'test'\\s*\\)\\s*\\{/",
            $src,
            "DatabaseSeeder must gate test-fixture seeders behind `if (env('SEEDING_MODE') === 'test')`"
        );

        // The pattern must be documented for future seeders to copy.
        $this->assertStringContainsString(
            "Pattern for any seeder adding test fixtures",
            $src,
            'DatabaseSeeder must document the gate pattern for future seeders'
        );
    }
}
