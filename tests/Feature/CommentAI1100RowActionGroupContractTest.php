<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI1100 — the four per-row actions on /admin/comments
 * (Edit / Delete / Approve / Mark spam) rendered as a row of four inline
 * buttons ("action sausage") at the end of every row. They are now collapsed
 * into a single ActionGroup kebab menu.
 *
 * Note: the companion "CONTENT column always —" half of AI-1100 was found to
 * be a seed-data artifact (all 127 seeded comments have rel_id = NULL), not a
 * relationship-wiring bug — the content() relation is correctly declared. No
 * code change is warranted for that half; it resolves once real comments
 * (which carry rel_id) exist.
 */
class CommentAI1100RowActionGroupContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'Modules/Comments/Filament/Resources/CommentResource.php'
        ));
    }

    #[Test]
    public function row_actions_are_collapsed_into_an_action_group(): void
    {
        $this->assertMatchesRegularExpression(
            '/->actions\(\s*\[\s*Tables\\\\Actions\\\\ActionGroup::make\(/s',
            $this->src,
            'The per-row actions must be wrapped in a single ActionGroup kebab menu.'
        );
    }

    #[Test]
    public function the_four_moderation_actions_are_inside_the_group(): void
    {
        // Slice the row-actions block so we assert membership within the group.
        $start = strpos($this->src, '->actions([');
        $this->assertNotFalse($start, 'Row actions block must exist.');
        $slice = substr($this->src, $start, 1400);

        foreach (["make('approve')", "make('spam')", 'EditAction::make()', 'DeleteAction::make()'] as $needle) {
            $this->assertStringContainsString($needle, $slice,
                "The grouped row actions must still include {$needle}.");
        }
    }
}
