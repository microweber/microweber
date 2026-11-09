<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract test for Swarm Mode Phase 2 — TRUE PARALLEL lanes.
 *
 * Phase 1 ran the lanes sequentially in one SSE request. Phase 2 adds
 * process-level parallelism: a JSON PLAN endpoint runs the planner once and
 * stores the full plan server-side, then the frontend fans the lanes out as
 * CONCURRENT requests to a single-lane SSE endpoint — each its own php process
 * with its own canvas-context binding, so concurrent lanes cannot clobber each
 * other. The sequential combined stream stays for back-compat.
 *
 * Source assertions are PRESENCE checks, so the selector-self-match guard does
 * not apply.
 */
class SwarmModePhase2ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    // ── Routes ────────────────────────────────────────────────────────────────

    #[Test]
    public function plan_route_is_registered(): void
    {
        $route = Route::getRoutes()->getByName('api.ai.agent-swarm-plan');
        $this->assertNotNull($route, 'POST api/ai/agent-swarm-plan must be registered.');
        $this->assertContains('POST', $route->methods());
        $this->assertSame('api/ai/agent-swarm-plan', $route->uri());
        $this->assertStringContainsString('agentSwarmPlan', $route->getActionName());
    }

    #[Test]
    public function lane_stream_route_is_registered(): void
    {
        $route = Route::getRoutes()->getByName('api.ai.agent-swarm-lane-stream');
        $this->assertNotNull($route, 'POST api/ai/agent-swarm-lane-stream must be registered.');
        $this->assertContains('POST', $route->methods());
        $this->assertSame('api/ai/agent-swarm-lane-stream', $route->uri());
        $this->assertStringContainsString('agentSwarmLaneStream', $route->getActionName());
    }

    #[Test]
    public function sequential_combined_stream_is_kept_for_back_compat(): void
    {
        $this->assertNotNull(
            Route::getRoutes()->getByName('api.ai.agent-swarm-stream'),
            'The Phase 1 sequential stream must remain for back-compat.'
        );
    }

    // ── Controller: plan caching, single-lane runner, shared runLane ──────────

    #[Test]
    public function controller_declares_the_phase2_methods(): void
    {
        foreach (['agentSwarmPlan', 'agentSwarmLaneStream', 'runLane'] as $m) {
            $this->assertTrue(
                method_exists(\Modules\Ai\Http\Controllers\SwarmController::class, $m),
                "SwarmController must declare {$m}()."
            );
        }
    }

    #[Test]
    public function plan_endpoint_caches_the_full_plan_server_side(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // Full plan (with worker prompts) is stored server-side under a plan id…
        $this->assertStringContainsString("Cache::put('swarm:plan:'", $src);
        // …and the lane stream looks it up by that id (prompts never ship to the client).
        $this->assertStringContainsString("Cache::get('swarm:plan:'", $src);
        // The JSON response hands the client only public lane meta + coordinator frames.
        $this->assertStringContainsString("'plan_id'", $src);
        $this->assertStringContainsString("'coordinator'", $src);
    }

    #[Test]
    public function both_paths_share_one_runLane_so_the_wire_contract_is_identical(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // The combined stream and the single-lane stream both delegate to runLane().
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($src, '$this->runLane('),
            'Both the combined and single-lane paths must call the shared runLane().'
        );
        // runLane binds the per-lane canvas context (isolation point).
        $this->assertStringContainsString("app()->instance('mw.ai.liveedit.context'", $src);
    }

    #[Test]
    public function lane_stream_resolves_the_lane_by_plan_id_and_agent_id(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        $this->assertStringContainsString("'plan_id' => 'required", $src);
        $this->assertStringContainsString("'agent_id' => 'required", $src);
    }

    // ── Frontend: plan-once then concurrent lane fan-out ──────────────────────

    #[Test]
    public function frontend_prefers_parallel_fan_out_with_sequential_fallback(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-swarm.js');
        // Plan once, then fan out.
        $this->assertStringContainsString('agent-swarm-plan', $src);
        $this->assertStringContainsString('agent-swarm-lane-stream', $src);
        // Concurrency: lanes opened together.
        $this->assertStringContainsString('Promise.all', $src);
        // Phase 2 helpers present.
        foreach (['_plan', '_fanOut', '_laneRequest', '_pumpStream'] as $fn) {
            $this->assertStringContainsString($fn, $src, "Swarm service must define {$fn}().");
        }
        // Sequential path retained as the fallback.
        $this->assertStringContainsString('_startSequential', $src);
    }

    #[Test]
    public function frontend_fan_out_still_does_one_save_and_reuses_applyEdit(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-swarm.js');
        $this->assertStringContainsString('saveCanvasAsync', $src, 'Parallel run must still fire ONE save at the end.');
        $this->assertStringContainsString('applyEdit', $src, 'Lanes must reuse MwAi applyEdit, not duplicate tool logic.');
        $this->assertStringContainsString('flushModuleReloads', $src);
    }

    // ── Shared header: brand set + header re-styled on every build ─────────────

    #[Test]
    public function planner_output_carries_a_brand_for_the_shared_header(): void
    {
        // The design DTO exposes a brand the coordinator sets as the site title.
        $design = $this->read('Modules/Ai/Agents/Output/SwarmDesignOutput.php');
        $this->assertStringContainsString('$brand', $design, 'SwarmDesignOutput must carry a brand for the header logo.');

        // The planner is told to produce design.brand.
        $planner = $this->read('Modules/Ai/Agents/SwarmPlannerAgent.php');
        $this->assertStringContainsString('design.brand', $planner, 'The planner must be instructed to return design.brand.');
    }

    #[Test]
    public function coordinator_locks_the_header_brand_before_lanes_run(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // The method exists and sets the site title option (what the logo renders).
        $this->assertTrue(
            method_exists(\Modules\Ai\Http\Controllers\SwarmController::class, 'lockHeaderBrand'),
            'SwarmController must declare lockHeaderBrand().'
        );
        $this->assertStringContainsString("'website_title'", $src, 'The brand must be written to the website_title option.');
        // The logo module renders its own per-instance text option (group is the
        // logo module id), not website_title — the brand must update those too.
        $this->assertStringContainsString("'%logo%'", $src, 'The brand must also update the logo module text option(s).');
        // Both the sequential stream and the parallel plan endpoint lock it.
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($src, '$this->lockHeaderBrand('),
            'Both the sequential stream and the plan endpoint must lock the header brand.'
        );
    }

    #[Test]
    public function global_css_restyles_the_shared_header_to_the_design(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // buildGlobalCss must bind the shared header/logo/nav to the tokens so a
        // new build overrides the header colours a previous build accumulated.
        $this->assertStringContainsString('.module-logo', $src, 'Global CSS must re-style the shared header logo.');
        $this->assertStringContainsString('.module-menu a', $src, 'Global CSS must re-style the shared header nav links.');
    }

    #[Test]
    public function navigation_subagent_wires_the_built_pages_into_the_menu(): void
    {
        // Route + endpoint for the post-lane navigation step.
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('api.ai.agent-swarm-nav');
        $this->assertNotNull($route, 'POST api/ai/agent-swarm-nav must be registered.');

        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        $this->assertTrue(method_exists(\Modules\Ai\Http\Controllers\SwarmController::class, 'agentSwarmNav'));
        $this->assertTrue(method_exists(\Modules\Ai\Http\Controllers\SwarmController::class, 'buildNavigation'));
        // It rebuilds the header menu fresh (drops the accumulated items) + clears
        // the menu cache so the new nav renders.
        $this->assertStringContainsString("'header_menu'", $src);
        $this->assertStringContainsString('clearMenuCache', $src);

        // The parallel frontend calls the nav endpoint after the lanes close.
        $swarmJs = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-swarm.js');
        $this->assertStringContainsString('agent-swarm-nav', $swarmJs);
    }

    #[Test]
    public function lane_prompt_drives_a_thorough_phased_build(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // Each lane builds the page in scoped phases (structure, content, effects,
        // QA) so pages are thorough and finished, not thin single-section stubs.
        $this->assertStringContainsString('BUILD THOROUGHLY IN PHASES', $src);
        $this->assertStringContainsString('STRUCTURE', $src);
        $this->assertStringContainsString('EFFECTS', $src);
        $this->assertStringContainsString('QA', $src);
        $this->assertStringContainsString('data-mw-reveal on EVERY section', $src);
    }

    #[Test]
    public function live_agent_cursors_are_wired_into_the_swarm(): void
    {
        // The cursor overlay component exists and exposes its lifecycle API.
        $cursors = $this->read('packages/frontend-assets/resources/assets/components/mw-ai-swarm-cursors.js');
        foreach (['start', 'tool', 'finishLane', 'finishAll'] as $m) {
            $this->assertStringContainsString($m, $cursors, "Cursor manager must expose {$m}().");
        }
        $this->assertStringContainsString('mwAiSwarmCursors', $cursors, 'Cursor manager must register a window singleton.');

        // It is imported wherever the board is, so both bundles get it.
        $sidebar = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-chat-sidebar.js');
        $this->assertStringContainsString('mw-ai-swarm-cursors.js', $sidebar);

        // The swarm turn drives the cursors alongside the board.
        $conv = $this->read('packages/frontend-assets/resources/assets/components/mw-ai-conversation.js');
        $this->assertStringContainsString('cursors.start(plan)', $conv, 'Cursors must start when the plan lands.');
        $this->assertStringContainsString('cursors.tool(agentId, edit)', $conv, 'Cursors must hop on each tool frame.');
        $this->assertStringContainsString('cursors.finishAll()', $conv);
    }

    #[Test]
    public function global_css_makes_the_language_switcher_inherit_the_palette(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        // The switcher reads its own surface/text tokens — map them to the palette.
        $this->assertStringContainsString('--mw-surface', $src, 'Global CSS must map the switcher surface token to the palette.');
        $this->assertStringContainsString('--mw-text-color', $src, 'Global CSS must map the switcher text token to the palette.');
        // The active language item must be overridden off a prior build's colour.
        $this->assertStringContainsString('mw-lang-switcher__item.is-active', $src, 'Global CSS must re-colour the active language item.');
    }
}
