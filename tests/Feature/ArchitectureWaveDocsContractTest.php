<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-113 — multi-ticket landing test for the
 * Architecture & Infrastructure Wave's documentation + skeleton
 * tickets.
 *
 * Pins:
 *   - AI-119 / TICKET-BA — docs/MIGRATION.md exists with the
 *     per-module migration inventory.
 *   - AI-119 / TICKET-BF — docs/DEEP_AUDIT_TODO.md documents the
 *     chunkById backfill convention.
 *   - AI-119 / TICKET-BI — docs/DEEP_AUDIT_TODO.md documents the
 *     FK + onDelete audit findings.
 *   - AI-119 / TICKET-BJ — docs/DEEP_AUDIT_TODO.md documents the
 *     cycle-43 encrypt extension scope.
 *   - AI-122 / TICKET-CB — PROJECT.md extended with the
 *     architecture overview + module map + deployment topology.
 *   - AI-122 / TICKET-CE — docs/MODULE_GUIDE.md exists.
 *   - AI-105 / TICKET-AY (phase 1) — Modules/Cart/Contracts/
 *     CartManagerContract.php exists with the canonical Cart API.
 *   - AI-106 / TICKET-AZ (phase 1) — event_log migration creates
 *     the table with name + payload + fired_at + replayed_at.
 *   - AI-125 / TICKET-CQ — _post-skin-base.scss + SKIN_GUIDE.md
 *     demonstrate the consolidation pattern.
 *
 * Style after the cycle-52..112 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ArchitectureWaveDocsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ai_119_ba_migration_md_exists(): void
    {
        $src = $this->read('docs/MIGRATION.md');

        $this->assertStringContainsString(
            '# Migration Inventory',
            $src,
            'docs/MIGRATION.md must have the canonical title'
        );

        // Must list at least the 4 commerce modules from
        // PROJECT.md.
        foreach (['Cart', 'Checkout', 'Coupons', 'Customer', 'Order'] as $module) {
            $this->assertStringContainsString(
                "`{$module}`",
                $src,
                "docs/MIGRATION.md must list module `{$module}`"
            );
        }
    }

    #[Test]
    public function ai_119_bf_bi_bj_deep_audit_todo_md_exists(): void
    {
        $src = $this->read('docs/DEEP_AUDIT_TODO.md');

        // Each of the 3 sub-tickets has a section.
        foreach ([
            'TICKET-BF — chunkById() backfill convention',
            'TICKET-BI — Foreign-key + onDelete audit',
            'TICKET-BJ — Extend cycle-43 encrypt sweep',
        ] as $heading) {
            $this->assertStringContainsString(
                $heading,
                $src,
                "docs/DEEP_AUDIT_TODO.md must contain section `{$heading}`"
            );
        }
    }

    #[Test]
    public function ai_122_cb_project_md_extended_with_module_map(): void
    {
        $src = $this->read('PROJECT.md');

        // The cycle-113 extension marker.
        $this->assertStringContainsString(
            'AI-122 / TICKET-CB extension (cycle-113',
            $src,
            'PROJECT.md must contain the AI-122 cycle-113 extension'
        );

        // Critical-ADR list.
        $this->assertStringContainsString(
            'ADR-0001 — Helper-layer security',
            $src,
            'PROJECT.md cycle-113 extension must list ADR-0001'
        );
    }

    #[Test]
    public function ai_122_ce_module_guide_exists(): void
    {
        $src = $this->read('docs/MODULE_GUIDE.md');

        $this->assertStringContainsString(
            '# Module Authoring Guide',
            $src,
            'docs/MODULE_GUIDE.md must have the canonical title'
        );

        // Key sections.
        foreach ([
            '## Module skeleton',
            '## Skins',
            '## Filament resources',
            '## Tests',
            '## Asset publishing',
        ] as $heading) {
            $this->assertStringContainsString(
                $heading,
                $src,
                "docs/MODULE_GUIDE.md must contain section `{$heading}`"
            );
        }
    }

    #[Test]
    public function ai_105_ay_cart_manager_contract_exists(): void
    {
        $src = $this->read('Modules/Cart/Contracts/CartManagerContract.php');

        $this->assertStringContainsString(
            'interface CartManagerContract',
            $src,
            'CartManagerContract must declare an interface'
        );

        // Required methods from the public surface.
        foreach ([
            'public function get_cart',
            'public function add_to_cart',
            'public function remove_from_cart',
            'public function update_qty',
            'public function empty_cart',
            'public function getCartAmount',
        ] as $method) {
            $this->assertStringContainsString(
                $method,
                $src,
                "CartManagerContract must declare `{$method}`"
            );
        }
    }

    #[Test]
    public function ai_106_az_event_log_migration_exists(): void
    {
        $src = $this->read('database/migrations/2026_05_09_000001_create_event_log_table.php');

        $this->assertStringContainsString(
            "Schema::create('event_log'",
            $src,
            'event_log migration must create the event_log table'
        );

        // Required columns from the brief.
        foreach (['name', 'payload', 'fired_at', 'replayed_at'] as $col) {
            $this->assertStringContainsString(
                $col,
                $src,
                "event_log migration must declare column `{$col}`"
            );
        }
    }

    #[Test]
    public function ai_125_cq_post_skin_base_scss_and_skin_guide_exist(): void
    {
        $scss = $this->read('Modules/Post/resources/assets/css/_post-skin-base.scss');
        $this->assertStringContainsString(
            '.post-skin-base',
            $scss,
            '_post-skin-base.scss must declare the .post-skin-base mixin'
        );
        $this->assertStringContainsString(
            '@extend .post-skin-base',
            $scss,
            '_post-skin-base.scss must demonstrate the @extend inheritance pattern'
        );

        $guide = $this->read('docs/SKIN_GUIDE.md');
        $this->assertStringContainsString(
            '# Skin Authoring Guide',
            $guide,
            'docs/SKIN_GUIDE.md must have the canonical title'
        );
        $this->assertStringContainsString(
            'AI-125 / TICKET-CQ',
            $guide,
            'docs/SKIN_GUIDE.md must reference the AI-125 ticket'
        );
    }
}
