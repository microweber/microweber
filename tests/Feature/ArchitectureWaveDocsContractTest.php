<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Architecture & Infrastructure Wave documentation + skeleton contract.
 *
 * Pins:
 *   - docs/MIGRATION.md exists with the per-module migration inventory.
 *   - docs/DEEP_AUDIT_TODO.md documents the chunkById backfill
 *     convention.
 *   - docs/DEEP_AUDIT_TODO.md documents the FK + onDelete audit
 *     findings.
 *   - docs/DEEP_AUDIT_TODO.md documents the encrypt extension scope.
 *   - PROJECT.md extended with the architecture overview + module map
 *     + deployment topology.
 *   - docs/MODULE_GUIDE.md exists.
 *   - Modules/Cart/Contracts/CartManagerContract.php exists with the
 *     canonical Cart API.
 *   - event_log migration creates the table with name + payload +
 *     fired_at + replayed_at.
 *   - _post-skin-base.scss + SKIN_GUIDE.md demonstrate the
 *     consolidation pattern.
 *
 * Contract test style: file-system reads only, no DB touch.
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

        // Each of the 3 sub-tickets has a section in the target doc.
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

        $this->assertStringContainsString(
            'AI-122 / TICKET-CB extension (cycle-113',
            $src,
            'PROJECT.md must contain the architecture extension marker'
        );

        // Critical-ADR list.
        $this->assertStringContainsString(
            'ADR-0001 — Helper-layer security',
            $src,
            'PROJECT.md must list ADR-0001 in the architecture overview'
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

        // Required methods from the public surface — aligned with the
        // actual CartManager. See ModuleContractsPhase1ContractTest for
        // the canonical signatures pin.
        foreach ([
            'public function get_cart',
            'public function update_cart',
            'public function remove_item',
            'public function update_item_qty',
            'public function empty_cart',
            'public function getTotal',
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
            'docs/SKIN_GUIDE.md must reference the skin-consolidation ticket marker'
        );
    }
}
