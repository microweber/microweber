<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-6027b9 / AI-788 CHANGE — Custom Field wizard
 * description consumer-wiring guard.
 * Jira: https://microweber.atlassian.net/browse/AI-788
 *
 * Designer's verify-before-accept on AI-788 (commit `9d6c261a08`)
 * caught a Stage-1 defect — the AI-788 ship updated
 * `CustomFieldTypes::getDescriptions()` with actionable copy
 * (PHPUnit DataProvider enforces ≥10 chars + non-tautological), but
 * the consumer site `ListCustomFields.php:266` had the
 * `->descriptions(CustomFieldTypes::class)` call commented out — so
 * the new copy data shipped + tested but never reached the user.
 * Runtime probe showed 0 description nodes per option in the
 * "Add custom field" RadioDeck slide-over.
 *
 * **Stage-1 defect family** (data shipped, consumer not wired) —
 * adjacent to:
 *   - AI-735 slice 1 (admin route propagation enabled but the
 *     fallback handler didn't distinguish admin URLs — AI-793 closed)
 *   - AI-771 cross-package @import (source rule shipped but the
 *     second pipeline's bundle wasn't rebuilt — AI-771 CHANGE closed)
 *
 * Diagnostic signature: source/data layer correct + contract tests
 * green + consumer site has the wiring call commented OR uses a
 * default that doesn't reach the new data.
 *
 * Fix: uncomment the `->descriptions(CustomFieldTypes::class)` call
 * at ListCustomFields.php inside the RadioDeck builder. Single-line
 * edit per designer dispatch.
 *
 * This test pins the consumer wiring so future refactors can't
 * re-comment OR remove the call without failing PHPUnit. Pairs
 * with AdminC188deAI788AI789FormWidgetUmbrellaContractTest which
 * pins the data layer.
 */
class Admin6027b9AI788DescriptionsWiredToWizardContractTest extends TestCase
{
    private string $listCustomFields;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listCustomFields = (string) file_get_contents(base_path(
            'Modules/CustomFields/Filament/Admin/ListCustomFields.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — descriptions call is wired (uncommented)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function descriptions_call_present_and_uncommented(): void
    {
        // Strip single-line // comments first so the AI-788 CHANGE
        // docblock prose (which legitimately mentions the call name)
        // doesn't false-pass. Block comments preserved (no `/*` style
        // comments referencing the call).
        $stripped = preg_replace('!//.*$!m', '', $this->listCustomFields);

        $this->assertStringContainsString(
            '->descriptions(CustomFieldTypes::class)',
            $stripped,
            'ListCustomFields RadioDeck builder must call ->descriptions(CustomFieldTypes::class) so the actionable copy shipped in CustomFieldTypes::getDescriptions() reaches the user.'
        );
    }

    #[Test]
    public function descriptions_call_immediately_follows_options_call(): void
    {
        // Pin the call's POSITION inside the RadioDeck builder so
        // future refactors that reorder fluent calls keep the
        // descriptions wired right next to options(). Defense
        // against silent regression where the line gets moved into
        // a conditional branch.
        $this->assertMatchesRegularExpression(
            '/->options\(CustomFieldTypes::class\)[\s\n]*(?:\/\/[^\n]*\n[\s]*)*->descriptions\(CustomFieldTypes::class\)/',
            $this->listCustomFields,
            'descriptions() call must immediately follow options() in the RadioDeck fluent chain (allowing for intervening // comments).'
        );
    }

    #[Test]
    public function descriptions_call_is_not_commented_out(): void
    {
        // Negative regression-guard against the exact defect designer
        // caught. The commented form was `// ->descriptions(...)` —
        // assert that exact form is gone.
        $this->assertDoesNotMatchRegularExpression(
            '!//\s*->descriptions\(CustomFieldTypes::class\)!',
            $this->listCustomFields,
            "Legacy `// ->descriptions(CustomFieldTypes::class)` commented-out form must be gone. If a future refactor needs to disable this temporarily, the right answer is to fix the underlying issue — not to silence the user-value contract."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai788_change_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-6027b9', $this->listCustomFields);
        $this->assertStringContainsString('AI-788 CHANGE', $this->listCustomFields);
    }
}
