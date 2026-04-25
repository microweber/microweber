<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\TestCase;

/**
 * Plan B.3 first-bullet contract — the {@see AssertsSkinBladeExists}
 * trait is the single source of truth for "is this skin's blade
 * actually on disk?". Per-skin Dusk tests compose it; if the trait
 * silently passes when a blade is missing OR fails noisily when it
 * isn't, every per-skin test inherits the bug.
 *
 * These feature-level tests pin the contract:
 *   1. A real shipped skin (`titles/skin-1`) MUST pass — sanity
 *      check that the path-derivation rule lines up with the actual
 *      repo layout. If somebody renames the
 *      `Templates/Bootstrap/resources/views/modules/layouts/templates/`
 *      directory, this test fires before every Dusk per-skin test
 *      starts mass-failing.
 *   2. A non-existent skin tag MUST fail — and the failure message
 *      MUST name the skin tag (so the operator can act on it
 *      without digging through stack traces).
 *
 * Lives under `tests/Feature/` rather than `tests/Unit/` because the
 * trait calls Laravel's `base_path()` helper, which requires the
 * application container to be booted (the `Tests\TestCase` base
 * class handles that via `CreatesApplication`).
 */
class AssertsSkinBladeExistsTraitTest extends TestCase
{
    use AssertsSkinBladeExists;

    #[Test]
    public function existing_skin_blade_passes_the_gate(): void
    {
        // titles/skin-1 has shipped since Phase-2 — it's the most
        // stable "this skin definitely exists" anchor in the
        // Bootstrap layouts catalog.
        $this->assertSkinBladeExists('titles/skin-1');
    }

    #[Test]
    public function missing_skin_blade_fails_the_gate_with_a_skin_naming_message(): void
    {
        $missingTag = 'features/skin-9999-this-skin-does-not-exist';

        try {
            $this->assertSkinBladeExists($missingTag);
            $this->fail("Expected AssertsSkinBladeExists to throw on missing skin '{$missingTag}'");
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString(
                $missingTag,
                $e->getMessage(),
                'Failure message must name the missing skin tag so the operator can act on it'
            );
            $this->assertStringContainsString(
                'blade file',
                $e->getMessage(),
                'Failure message must mention "blade file" so the operator knows the gate is about disk presence, not factory wiring'
            );
        }
    }
}
