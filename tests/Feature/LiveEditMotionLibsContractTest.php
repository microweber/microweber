<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\MetaTags\Entities\MotionLibsHeadTags;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: the AI agents get "motion design" — GSAP + ScrollTrigger are loaded
 * on the public frontend, the agents are told which JS libraries are loaded, and
 * the Live-Edit agent knows the GSAP + raw-rAF motion pattern.
 */
class LiveEditMotionLibsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    #[Test]
    public function gsap_head_entity_renders_gsap_and_scrolltrigger_in_the_head(): void
    {
        $e = new MotionLibsHeadTags();
        $this->assertSame('head', $e->getPlacement());
        $html = $e->toHtml();
        $this->assertStringContainsString('gsap/3.13.0/gsap.min.js', $html);
        $this->assertStringContainsString('ScrollTrigger.min.js', $html);
        $this->assertStringContainsString('registerPlugin', $html);
        $this->assertStringContainsString('defer', $html, 'Motion libs must load deferred (non-blocking).');
    }

    #[Test]
    public function motion_libs_are_registered_on_the_frontend_package(): void
    {
        $provider = $this->read('packages/microweber-meta-tags/src/Providers/MetaTagsServiceProvider.php');
        $this->assertStringContainsString("'motion_libs'", $provider, 'motion_libs package must be required by frontend.');
        $this->assertStringContainsString('MotionLibsHeadTags', $provider, 'The GSAP entity must be registered.');
    }

    #[Test]
    public function agents_are_told_which_libraries_are_loaded(): void
    {
        // Shared hint trait, used by BOTH the chat and the swarm controllers.
        $trait = $this->read('Modules/Ai/Support/LibrariesHint.php');
        $this->assertStringContainsString('Loaded JS libraries on the published page', $trait);

        $chat = $this->read('Modules/Ai/Http/Controllers/AiController.php');
        $this->assertStringContainsString('use LibrariesHint;', $chat);
        $this->assertStringContainsString("'libraries' => \$libraries", $chat, 'Libraries must be bound into the canvas context.');
        $this->assertStringContainsString('formatLibrariesHint', $chat);

        $swarm = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        $this->assertStringContainsString('use LibrariesHint;', $swarm);
        $this->assertStringContainsString('formatLibrariesHint', $swarm, 'Swarm lanes must get the libraries hint too.');
    }

    #[Test]
    public function frontend_detects_and_sends_loaded_libraries(): void
    {
        $mwAi = $this->read('Modules/Ai/resources/assets/js/mw-ai.js');
        $this->assertStringContainsString('collectLibraries', $mwAi);
        $this->assertStringContainsString('body.libraries', $mwAi, 'The chat request must carry detected libraries.');
        $this->assertStringContainsString("add('gsap'", $mwAi, 'GSAP must be among the detected libraries.');
    }

    #[Test]
    public function live_edit_agent_knows_the_gsap_plus_raf_motion_pattern(): void
    {
        $agent = $this->read('Modules/Ai/Agents/LiveEditAgent.php');
        $this->assertStringContainsString('MOTION DESIGN WITH GSAP', $agent);
        $this->assertStringContainsString('requestAnimationFrame', $agent);
        $this->assertStringContainsString('ScrollTrigger', $agent);
        $this->assertStringContainsString('Loaded JS libraries', $agent, 'The agent must be told to use only loaded libraries.');
    }
}
