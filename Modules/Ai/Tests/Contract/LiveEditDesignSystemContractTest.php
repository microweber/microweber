<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for the design-system rules distilled into the Live-Edit
 * AI agent's system prompt (Modules/Ai/Agents/LiveEditAgent.php) from the
 * MIT-licensed elayadesign/ai-design-skills "landing-page-design" skill.
 *
 * These rules are what lift the quality of pages the agent BUILDS (type/spacing/
 * radius scales, flat surfaces, motion curve, interactive states, landing-page
 * section structure, real/specific copy, ship basics). The agent drives its whole
 * behaviour from this prompt, so if a rule silently drops out, the skill is gone.
 * We assert on the source text (like the sibling LiveEditGuardsContractTest) so
 * the check runs pre-boot and does not depend on the AI runtime.
 */
final class LiveEditDesignSystemContractTest extends TestCase
{
    private function agentSource(): string
    {
        // Modules/Ai/Tests/Contract -> repo root is four levels up.
        $path = dirname(__DIR__, 4) . '/Modules/Ai/Agents/LiveEditAgent.php';
        $this->assertFileExists($path, 'LiveEditAgent.php is missing.');

        return (string) file_get_contents($path);
    }

    /**
     * Each case: a substring that MUST appear in the agent prompt, and why.
     * @return array<string, array{0:string,1:string}>
     */
    public static function designRuleProvider(): array
    {
        return [
            'attribution to the source skill' => [
                'ai-design-skills',
                'The distilled rules must credit the MIT ai-design-skills source in a code comment.',
            ],
            'font-size scale' => [
                '12, 14, 16, 18, 20, 24, 30, 36, 48, 60, 72px',
                'The type scale must be pinned so the agent snaps font sizes instead of inventing them.',
            ],
            'spacing scale' => [
                '0, 2, 4, 8, 12, 16, 24, 32, 40, 48, 64, 80, 96px',
                'The spacing scale must be pinned for padding/margin/gap.',
            ],
            'nested radius formula' => [
                'inner-radius = outer minus gap',
                'The nested-radius rule must be present so nested shapes read correctly.',
            ],
            'reuse template font tokens' => [
                '--mw-body-font-family',
                'The agent must reuse the template font tokens, not force a generic font.',
            ],
            'flat backgrounds' => [
                'backgrounds are FLAT colours',
                'Backgrounds must be flat (no gradients) except the allowed hero-heading text gradient.',
            ],
            'motion base curve' => [
                'cubic-bezier(0.22, 1, 0.36, 1)',
                'The weighted motion curve must be pinned (never a browser-default curve).',
            ],
            'reduced motion' => [
                'prefers-reduced-motion: reduce',
                'Motion must respect prefers-reduced-motion.',
            ],
            'active state' => [
                'transform: scale(0.98)',
                'Interactive elements must ship an active-state feedback.',
            ],
            'focus ring' => [
                ':focus-visible focus ring',
                'A visible focus ring is an accessibility requirement, not optional.',
            ],
            'landing page structure' => [
                'LANDING / MARKETING PAGE STRUCTURE',
                'The ordered landing-page section structure must be present.',
            ],
            'build whole page in one go' => [
                'BUILD THE WHOLE PAGE IN ONE GO',
                'The agent must build every section in one pass before verifying, never stop after the hero.',
            ],
            'no inspect between sections' => [
                'Do NOT call get_dom / get_computed_styles / screenshot BETWEEN sections',
                'Per-section inspection during the initial build must be banned so the whole page gets built.',
            ],
            'no narration until built' => [
                'DO NOT NARRATE OR STATE A PLAN until the page is FULLY built',
                'A text reply ends the tool loop, so the agent must stay silent (no plan message either) until every section is built.',
            ],
            'hero one primary CTA' => [
                'ONE primary CTA',
                'The hero must carry exactly one primary CTA (no competing CTAs above the fold).',
            ],
            'final CTA mirrors hero' => [
                'FINAL CTA — identical to the hero CTA',
                'The final CTA must mirror the hero CTA.',
            ],
            'real specific copy' => [
                'never lorem ipsum',
                'Copy must be real and specific, never placeholder.',
            ],
            'banned filler words' => [
                'BANNED filler words',
                'The filler-word ban must be present so copy stays plain.',
            ],
            'ship basics' => [
                'FINISH LIKE A REAL SITE',
                'Ship basics (footer legal links, alt text, semantic HTML, meta) must be present.',
            ],
            'section layout containment' => [
                'SECTION LAYOUT',
                'Every section must center its content in a max-width container and use 1fr grids so cards fill the width.',
            ],
            'avatars never empty' => [
                'AVATARS ARE NEVER EMPTY CIRCLES',
                'Testimonial/team avatars must show initials or a portrait, never a blank disc.',
            ],
            'shop add to cart module' => [
                'insert_module type "shop/products"',
                'A working add-to-cart must come from the shop/products module, not static HTML.',
            ],
            'discover module option keys' => [
                'DISCOVER ITS OPTION KEYS',
                'The agent must call get_module_settings(type=...) to learn real option keys before set_module_option.',
            ],
            'list modules use add_list_item' => [
                'LIST MODULES (faq, tabs, accordion, teamcard, testimonials)',
                'List modules hold items, not flat options; the agent must fill them with add_list_item, not set_module_option or static HTML.',
            ],
        ];
    }

    #[Test]
    #[DataProvider('designRuleProvider')]
    public function agent_prompt_pins_design_rule(string $needle, string $why): void
    {
        $this->assertStringContainsString($needle, $this->agentSource(), $why);
    }

    #[Test]
    public function design_rules_sit_before_the_tools_list(): void
    {
        // The distilled block is injected after the validation guidance and
        // before the "Your tools:" enumeration — pin that ordering so a future
        // edit does not accidentally move it out of the background section.
        $src = $this->agentSource();
        $design = strpos($src, 'DESIGN SYSTEM — snap every value to a scale');
        $tools = strpos($src, "'Your tools: get_page_context");
        $this->assertNotFalse($design, 'The DESIGN SYSTEM rule must be present.');
        $this->assertNotFalse($tools, 'The tools enumeration must be present.');
        $this->assertLessThan($tools, $design, 'The design-system block must sit before the tools list.');
    }

    #[Test]
    public function tool_run_cap_is_raised_for_full_page_builds(): void
    {
        // NeuronAI caps each tool at 10 runs per session by default and throws
        // ToolRunsExceededException on the next call. A full landing page is 7+
        // add_section calls, so the default cap crashes mid-build. The agent must
        // raise it.
        $this->assertStringContainsString(
            '$this->toolMaxRuns(',
            $this->agentSource(),
            'LiveEditAgent must raise toolMaxRuns so a full-page build does not hit the default 10-run cap.'
        );
    }

    #[Test]
    public function add_list_item_tool_is_registered_and_echoed_on_the_canvas(): void
    {
        // The list-module prompt rule is useless unless the tool is actually
        // wired into the agent AND the frontend echoes it (reloads the module),
        // so a FAQ/tabs/team item shows on the canvas without a full refresh.
        $this->assertStringContainsString('new AddListItemTool(', $this->agentSource(),
            'LiveEditAgent must register AddListItemTool in setupTools().');

        $js = dirname(__DIR__, 4) . '/Modules/Ai/resources/assets/js/mw-ai.js';
        $this->assertFileExists($js, 'mw-ai.js is missing.');
        $this->assertMatchesRegularExpression('~add_list_item:\s*function~', (string) file_get_contents($js),
            'mw-ai.js must implement the add_list_item frontend echo (module reload).');
    }

    #[Test]
    public function controller_has_a_half_built_page_backstop(): void
    {
        // If the model narrates the next sections as prose instead of calling the
        // tools, NeuronAI's loop ends and the page is left half-built. The stream
        // controller must detect an unfinished liveedit build and nudge the model
        // to continue (bounded).
        $path = dirname(__DIR__, 4) . '/Modules/Ai/Http/Controllers/AiController.php';
        $this->assertFileExists($path, 'AiController.php is missing.');
        $src = (string) file_get_contents($path);

        $this->assertStringContainsString('countEmittedTool', $src,
            'The controller must count built sections to detect an unfinished build.');
        $this->assertStringContainsString("\$agent->chat(\$nudge)", $src,
            'The controller must re-prompt the agent to continue an unfinished build.');
    }
}
