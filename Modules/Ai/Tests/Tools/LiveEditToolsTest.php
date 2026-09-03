<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Services\ToolCallCollector;
use Modules\Ai\Tools\LiveEdit\ApplyCssTool;
use Modules\Ai\Tools\LiveEdit\GetPageContextTool;
use Modules\Ai\Tools\LiveEdit\SetImageTool;
use Modules\Ai\Tools\LiveEdit\SetTextTool;
use NeuronAI\Observability\Events\ToolCalled;
use PHPUnit\Framework\Attributes\Test;

/**
 * Contract tests for the Live-Edit frontend tools.
 *
 * These tools are FRONTEND tools: the model calls them, but the real work runs
 * in the browser on the live canvas and persists via the normal Live-Edit SAVE.
 * The backend classes must therefore stay side-effect-free command emitters, and
 * the agent-chat-stream endpoint surfaces each call (name + args) to the canvas
 * via ToolCallCollector / SseToolEmitter. These tests pin exactly that: valid
 * calls echo an apply-confirmation (never persist), bad args return the error
 * marker, and the collector reports the { tool, args } shape the frontend needs.
 */
class LiveEditToolsTest extends ToolTestCase
{
    #[Test]
    public function apply_css_echoes_the_css_and_is_side_effect_free(): void
    {
        $css = 'h1 { color: #e63946; } .btn { border-radius: 8px; }';
        $tool = new ApplyCssTool();

        $result = $tool->__invoke(css: $css);

        // Echoes the CSS back so the model can confirm; not an error.
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $result);
        $this->assertStringContainsString($css, $result);
        // Guidance makes the frontend-apply + user-SAVE contract explicit.
        $this->assertStringContainsString('Save', $result);
    }

    #[Test]
    public function apply_css_rejects_empty_input(): void
    {
        $tool = new ApplyCssTool();

        $result = $tool->__invoke(css: '   ');

        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $result);
    }

    #[Test]
    public function set_text_confirms_valid_call_and_rejects_missing_selector(): void
    {
        $tool = new SetTextTool();

        $ok = $tool->__invoke(selector: 'h1', text: 'Hello world');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);
        $this->assertStringContainsString('h1', $ok);

        $bad = $tool->__invoke(selector: '', text: 'x');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $bad);
    }

    #[Test]
    public function set_image_requires_both_selector_and_url(): void
    {
        $tool = new SetImageTool();

        $ok = $tool->__invoke(selector: '.hero img', url: 'https://example.com/a.jpg');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);

        $missingUrl = $tool->__invoke(selector: 'img', url: '');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $missingUrl);

        $missingSelector = $tool->__invoke(selector: '', url: 'https://example.com/a.jpg');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $missingSelector);
    }

    #[Test]
    public function tools_expose_the_expected_names(): void
    {
        $this->assertSame('apply_css', (new ApplyCssTool())->getName());
        $this->assertSame('set_text', (new SetTextTool())->getName());
        $this->assertSame('set_image', (new SetImageTool())->getName());
        $this->assertSame('get_page_context', (new GetPageContextTool())->getName());
    }

    #[Test]
    public function collector_reports_tool_name_and_args_for_the_frontend(): void
    {
        $collector = new ToolCallCollector();

        $tool = (new ApplyCssTool())->setInputs(['css' => 'a { color: red; }']);
        $collector->onEvent('tool-called', $this, new ToolCalled($tool));

        $calls = $collector->all();
        $this->assertCount(1, $calls);
        $this->assertSame('apply_css', $calls[0]['tool']);
        $this->assertSame(['css' => 'a { color: red; }'], $calls[0]['args']);
    }
}
