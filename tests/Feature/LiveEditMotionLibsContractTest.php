<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\MetaTags\Entities\MotionLibsHeadTags;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: the AI agents get "motion design" — GSAP + ScrollTrigger + the
 * data-attribute motion kit are loaded on the public frontend as a SINGLE
 * self-hosted file (no cdnjs), the agents are told which JS libraries are
 * loaded, and the Live-Edit agent knows the GSAP + raw-rAF motion pattern.
 */
class LiveEditMotionLibsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    #[Test]
    public function gsap_is_served_as_a_single_self_hosted_bundle_in_the_footer(): void
    {
        $e = new MotionLibsHeadTags();
        // Loaded at body-end (footer), not the head — deferred + runtime-guarded.
        $this->assertSame('footer', $e->getPlacement());
        $html = $e->toHtml();

        // Exactly ONE script tag — the combined bundle, not two CDN scripts + an
        // inline kit.
        $this->assertSame(1, substr_count($html, '<script'), 'Motion must load via a single <script> bundle.');
        // Self-hosted from frontend-assets-libs, not cdnjs.
        $this->assertStringContainsString('frontend-assets-libs/gsap/mw-motion.js', $html);
        $this->assertStringNotContainsString('cdnjs.cloudflare.com', $html, 'GSAP must be self-hosted, not loaded from cdnjs.');
        // Deferred + still detectable by the agents.
        $this->assertStringContainsString('defer', $html, 'Motion libs must load deferred (non-blocking).');
        $this->assertStringContainsString('data-mw-motion-lib="gsap"', $html, 'The bundle must stay detectable as the gsap motion lib.');
    }

    #[Test]
    public function the_bundle_file_contains_gsap_scrolltrigger_and_the_motion_kit(): void
    {
        // Assert the TRACKED source (local-libs), not the built dist/ copy, so the
        // contract holds on a fresh checkout without running the npm build.
        $bundle = base_path('packages/frontend-assets-libs/resources/local-libs/gsap/mw-motion.js');
        $this->assertFileExists($bundle, 'The combined motion bundle must be vendored in the package.');
        $js = (string) file_get_contents($bundle);

        // Self-hosted GSAP + ScrollTrigger are concatenated into the one file.
        $this->assertStringContainsString('GSAP ' . MotionLibsHeadTags::GSAP_VERSION, $js, 'Pinned GSAP version must be vendored.');
        $this->assertStringContainsString('ScrollTrigger', $js);
        $this->assertStringContainsString('registerPlugin', $js);

        // The data-attribute motion engine: reveals + parallax + one rAF loop.
        $this->assertStringContainsString('data-mw-reveal', $js);
        $this->assertStringContainsString('data-mw-parallax', $js);
        $this->assertStringContainsString('IntersectionObserver', $js, 'Reveals use IntersectionObserver (reliable one-shot enter).');
        $this->assertStringContainsString('requestAnimationFrame', $js, 'Continuous motion runs on one rAF loop.');
        $this->assertStringContainsString('mwMotionKit', $js, 'The kit must re-scan dynamically-inserted sections.');
    }

    #[Test]
    public function the_bundle_is_wired_into_the_asset_build(): void
    {
        // The build (build.mjs -> config-common.js) must copy the gsap source into
        // dist/, which the package then publishes to public/. Asserting the build
        // wiring is durable on a fresh checkout (dist/ + public/ are build outputs,
        // gitignored, and need not exist when PHPUnit runs).
        $buildConfig = $this->read('packages/frontend-assets-libs/config-common.js');
        $this->assertStringContainsString("target: `gsap`", $buildConfig, 'The build must copy the gsap bundle into dist/.');
        $this->assertStringContainsString('resources/local-libs/gsap', $buildConfig);

        // The entity references exactly the published path the build produces.
        $this->assertStringContainsString(
            'vendor/microweber-packages/frontend-assets-libs/gsap/mw-motion.js',
            (new MotionLibsHeadTags())->toHtml()
        );
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
        // The reliable attribute-driven path (the motion kit).
        $this->assertStringContainsString('data-mw-reveal', $agent, 'The agent must know the motion-kit reveal attribute.');
        $this->assertStringContainsString('data-mw-parallax', $agent, 'The agent must know the motion-kit parallax attribute.');
    }
}
