<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract test for the Swarm QA reviewers — two dedicated agents that run
 * AFTER every page lane (and the navigation subagent) to sweep the OPEN page
 * for defects a fast first-pass build leaves behind:
 *   • qa-contrast — colours that blend (white-on-white text, an icon the same
 *     colour as its bar, a button label lost on its button);
 *   • qa-spacing  — spacing mismatches (cramped logo, uneven card gaps,
 *     inconsistent section padding, touching elements).
 *
 * Each reviewer reads the live page (get_dom / get_css_vars) and FIXES only its
 * own class of defect in place (apply_css) — never deleting, never crossing into
 * the other reviewer's concern, so the two can run concurrently without colliding.
 *
 * Source assertions are PRESENCE checks, so the selector-self-match guard does
 * not apply.
 */
class SwarmQaReviewersContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    // ── Route ─────────────────────────────────────────────────────────────────

    #[Test]
    public function qa_stream_route_is_registered(): void
    {
        $route = Route::getRoutes()->getByName('api.ai.agent-swarm-qa-stream');
        $this->assertNotNull($route, 'POST api/ai/agent-swarm-qa-stream must be registered.');
        $this->assertContains('POST', $route->methods());
        $this->assertSame('api/ai/agent-swarm-qa-stream', $route->uri());
        $this->assertStringContainsString('agentSwarmQaStream', $route->getActionName());
    }

    // ── Controller ────────────────────────────────────────────────────────────

    #[Test]
    public function controller_declares_the_qa_methods(): void
    {
        foreach (['agentSwarmQaStream', 'runQaLane', 'buildQaPrompt', 'qaReviewers'] as $m) {
            $this->assertTrue(
                method_exists(\Modules\Ai\Http\Controllers\SwarmController::class, $m),
                "SwarmController must declare {$m}()."
            );
        }
    }

    #[Test]
    public function both_reviewers_are_defined_with_their_agent_ids(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // The two reviewer identities the board shows as their own agents.
        $this->assertStringContainsString("'qa-contrast'", $src);
        $this->assertStringContainsString("'qa-spacing'", $src);
        $this->assertStringContainsString('Contrast QA', $src);
        $this->assertStringContainsString('Spacing QA', $src);
        // Focus is constrained to the two reviewers (no arbitrary worker spawn).
        $this->assertStringContainsString("'focus' => 'required|string|in:contrast,spacing'", $src);
    }

    #[Test]
    public function qa_lane_reuses_the_lane_plumbing_and_emits_lane_tagged_frames(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // Reviewer runs a real LiveEdit worker on its own chat thread…
        $this->assertStringContainsString("agentType: 'liveedit'", $src);
        // …bound to the live open-page canvas context (same isolation point as runLane)…
        $this->assertStringContainsString("app()->instance('mw.ai.liveedit.context'", $src);
        // …and emits lane-tagged frames via the same SseToolEmitter($onToolCall, $agentId).
        $this->assertStringContainsString('new SseToolEmitter($onToolCall, $agentId)', $src);
    }

    #[Test]
    public function qa_runs_after_pages_and_nav_in_the_sequential_path(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // The sequential combined stream wires QA after buildNavigation().
        $navPos = strpos($src, '$this->buildNavigation($plan, $sse, $openContentId);');
        $qaPos = strpos($src, 'foreach ($this->qaReviewers() as $reviewer) {');
        $this->assertNotFalse($navPos, 'Sequential path must call buildNavigation().');
        $this->assertNotFalse($qaPos, 'Sequential path must loop qaReviewers().');
        $this->assertGreaterThan($navPos, $qaPos, 'QA must run AFTER the navigation subagent.');
    }

    #[Test]
    public function contrast_and_spacing_prompts_stay_in_their_own_lane(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // The contrast reviewer changes ONLY colour.
        $this->assertStringContainsString('Change ONLY colour — never spacing, layout or copy.', $src);
        // The spacing reviewer changes ONLY spacing/alignment.
        $this->assertStringContainsString('Change ONLY spacing, alignment and internal padding — never colour, never copy, never delete.', $src);
        // Both read the real page before fixing, and never delete.
        $this->assertStringContainsString('call get_dom', $src);
    }

    // ── Frontend ──────────────────────────────────────────────────────────────

    #[Test]
    public function frontend_fans_out_both_qa_reviewers_after_the_build(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-swarm.js');
        // A dedicated request opener for a QA reviewer stream.
        $this->assertStringContainsString('_qaRequest', $src, 'Swarm service must define _qaRequest().');
        $this->assertStringContainsString('agent-swarm-qa-stream', $src);
        // Both reviewers are fanned out, keyed by focus.
        $this->assertStringContainsString('focus: "contrast"', $src);
        $this->assertStringContainsString('focus: "spacing"', $src);
        $this->assertStringContainsString('qa-contrast', $src);
        $this->assertStringContainsString('qa-spacing', $src);
    }

    #[Test]
    public function qa_fan_out_runs_before_the_single_save(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-swarm.js');
        // The QA reviewers must run before the one whole-run save, so their fixes
        // land in the saved canvas.
        $qaPos = strpos($src, '_qaRequest');
        $savePos = strrpos($src, 'saveCanvasAsync');
        $this->assertNotFalse($qaPos);
        $this->assertNotFalse($savePos);
        $this->assertLessThan($savePos, $qaPos, 'QA reviewers must run before the final saveCanvasAsync().');
    }
}
