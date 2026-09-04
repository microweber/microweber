<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Services\ToolCallCollector;
use Modules\Ai\Tools\LiveEdit\AddSectionTool;
use Modules\Ai\Tools\LiveEdit\ApplyCssTool;
use Modules\Ai\Tools\LiveEdit\GetPageContextTool;
use Modules\Ai\Tools\LiveEdit\InsertModuleTool;
use Modules\Ai\Tools\LiveEdit\NavigateToPageTool;
use Modules\Ai\Tools\LiveEdit\SavePageTool;
use Modules\Ai\Tools\LiveEdit\SetImageTool;
use Modules\Ai\Tools\LiveEdit\SetModuleOptionTool;
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
    public function add_section_confirms_valid_html_and_rejects_module_tags(): void
    {
        $tool = new AddSectionTool();

        $ok = $tool->__invoke(html: '<section class="hero"><h1>Hi</h1></section>', css: '.hero{color:#fff}');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);

        // Structural safety: never let Microweber <module> markup through.
        $bad = $tool->__invoke(html: '<module type="contact_form"/>');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $bad);

        $empty = $tool->__invoke(html: '   ');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $empty);
    }

    #[Test]
    public function insert_module_requires_a_type(): void
    {
        $tool = new InsertModuleTool();
        $ok = $tool->__invoke(type: 'contact_form', position: 'bottom');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);
        $this->assertStringContainsString('contact_form', $ok);

        $bad = $tool->__invoke(type: '');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $bad);
    }

    #[Test]
    public function set_module_option_requires_a_key(): void
    {
        $tool = new SetModuleOptionTool();
        $ok = $tool->__invoke(key: 'email', value: 'hi@example.com');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);

        $bad = $tool->__invoke(key: '', value: 'x');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $bad);
    }

    #[Test]
    public function navigate_and_save_tools_behave(): void
    {
        $nav = new NavigateToPageTool();
        $ok = $nav->__invoke(url: 'features');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);
        $this->assertStringContainsString('features', $ok);
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $nav->__invoke(url: ''));

        // save_page is a side-effect-free frontend trigger — always confirms.
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, (new SavePageTool())->__invoke());
    }

    #[Test]
    public function tools_expose_the_expected_names(): void
    {
        $this->assertSame('add_section', (new AddSectionTool())->getName());
        $this->assertSame('insert_module', (new InsertModuleTool())->getName());
        $this->assertSame('set_module_option', (new SetModuleOptionTool())->getName());
        $this->assertSame('apply_css', (new ApplyCssTool())->getName());
        $this->assertSame('set_text', (new SetTextTool())->getName());
        $this->assertSame('set_image', (new SetImageTool())->getName());
        $this->assertSame('get_page_context', (new GetPageContextTool())->getName());
        $this->assertSame('navigate_to_page', (new NavigateToPageTool())->getName());
        $this->assertSame('save_page', (new SavePageTool())->getName());
        $this->assertSame('get_menu', (new \Modules\Ai\Tools\LiveEdit\GetMenuTool())->getName());
        $this->assertSame('edit_menu_item', (new \Modules\Ai\Tools\LiveEdit\EditMenuItemTool())->getName());
        $this->assertSame('insert_layout', (new \Modules\Ai\Tools\LiveEdit\InsertLayoutTool())->getName());
        $this->assertSame('get_module_settings', (new \Modules\Ai\Tools\LiveEdit\GetModuleSettingsTool())->getName());
        $this->assertSame('add_form_field', (new \Modules\Ai\Tools\LiveEdit\AddFormFieldTool())->getName());
        $this->assertSame('get_modules', (new \Modules\Ai\Tools\LiveEdit\GetModulesTool())->getName());
        $this->assertSame('get_layouts', (new \Modules\Ai\Tools\LiveEdit\GetLayoutsTool())->getName());
        $this->assertSame('get_dom', (new \Modules\Ai\Tools\LiveEdit\GetDomTool())->getName());
        $this->assertSame('get_edit_fields', (new \Modules\Ai\Tools\LiveEdit\GetEditFieldsTool())->getName());
        $this->assertSame('get_computed_styles', (new \Modules\Ai\Tools\LiveEdit\GetComputedStylesTool())->getName());
        $this->assertSame('delete_element', (new \Modules\Ai\Tools\LiveEdit\DeleteElementTool())->getName());
        $this->assertSame('move_element', (new \Modules\Ai\Tools\LiveEdit\MoveElementTool())->getName());
        $this->assertSame('duplicate_element', (new \Modules\Ai\Tools\LiveEdit\DuplicateElementTool())->getName());
        $this->assertSame('set_link', (new \Modules\Ai\Tools\LiveEdit\SetLinkTool())->getName());
        $this->assertSame('get_selected_element', (new \Modules\Ai\Tools\LiveEdit\GetSelectedElementTool())->getName());
        $this->assertSame('get_selected_layout', (new \Modules\Ai\Tools\LiveEdit\GetSelectedLayoutTool())->getName());
    }

    #[Test]
    public function get_selected_element_resolves_this_from_the_selection_context(): void
    {
        app()->instance('mw.ai.liveedit.context', [
            'selected_element' => ['selector' => '#hero-1', 'tag' => 'h1', 'text' => 'Hello'],
            'selected_layout' => ['selector' => '#sec-1', 'tag' => 'section'],
        ]);
        $el = json_decode((new \Modules\Ai\Tools\LiveEdit\GetSelectedElementTool())->__invoke(), true);
        $this->assertSame('#hero-1', $el['selected_element']['selector']);
        $lay = json_decode((new \Modules\Ai\Tools\LiveEdit\GetSelectedLayoutTool())->__invoke(), true);
        $this->assertSame('#sec-1', $lay['selected_layout']['selector']);

        // No selection -> a clean error telling the model to ask the user to click.
        app()->forgetInstance('mw.ai.liveedit.context');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, (new \Modules\Ai\Tools\LiveEdit\GetSelectedElementTool())->__invoke());
    }

    #[Test]
    public function canvas_manipulation_tools_confirm_valid_calls_and_reject_missing_selector(): void
    {
        $del = new \Modules\Ai\Tools\LiveEdit\DeleteElementTool();
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $del->__invoke(selector: '.pixel-hero'));
        $this->assertStringContainsString('.pixel-hero', $del->__invoke(selector: '.pixel-hero'));
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $del->__invoke(selector: ''));

        $move = new \Modules\Ai\Tools\LiveEdit\MoveElementTool();
        $ok = $move->__invoke(selector: '.card', direction: 'down');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);
        $this->assertStringContainsString('down', $ok);
        // Unknown direction falls back to "up" (never errors on a bad direction).
        $this->assertStringContainsString('up', $move->__invoke(selector: '.card', direction: 'sideways'));
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $move->__invoke(selector: ''));

        $dup = new \Modules\Ai\Tools\LiveEdit\DuplicateElementTool();
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $dup->__invoke(selector: '.card'));
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $dup->__invoke(selector: ''));

        $link = new \Modules\Ai\Tools\LiveEdit\SetLinkTool();
        $this->assertStringContainsString('https://x.io', $link->__invoke(selector: '.cta', url: 'https://x.io'));
        // Empty url = remove the link (still a valid, non-error call).
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $link->__invoke(selector: '.cta', url: ''));
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $link->__invoke(selector: '', url: 'https://x.io'));
    }

    #[Test]
    public function get_computed_styles_returns_rendered_css_and_filters_by_selector(): void
    {
        app()->instance('mw.ai.liveedit.context', [
            'computed_styles' => [
                ['selector' => 'nav', 'text' => 'PIXEL OFFICE', 'background' => 'rgba(0, 0, 0, 0)', 'padding' => '0px'],
                ['selector' => '.pixel-hero', 'text' => 'Hi', 'background' => 'rgb(11, 31, 69)', 'padding' => '64px'],
            ],
        ]);
        $tool = new \Modules\Ai\Tools\LiveEdit\GetComputedStylesTool();

        $all = json_decode($tool->__invoke(), true);
        $this->assertSame(2, $all['count']);

        $nav = json_decode($tool->__invoke(selector: 'nav'), true);
        $this->assertSame(1, $nav['count']);
        $this->assertSame('nav', $nav['elements'][0]['selector']);

        // No context bound -> clean error.
        app()->forgetInstance('mw.ai.liveedit.context');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, (new \Modules\Ai\Tools\LiveEdit\GetComputedStylesTool())->__invoke());
    }

    #[Test]
    public function get_dom_returns_the_bound_canvas_and_can_narrow_by_selector(): void
    {
        app()->instance('mw.ai.liveedit.context', [
            'dom' => '<body><div class="edit" rel="content" field="content"><h1 id="hero">Hi</h1></div></body>',
            'edit_fields' => [],
        ]);
        $tool = new \Modules\Ai\Tools\LiveEdit\GetDomTool();

        $all = $tool->__invoke();
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $all);
        $this->assertStringContainsString('hero', $all);

        $part = json_decode($tool->__invoke(selector: '#hero'), true);
        $this->assertStringContainsString('<h1', $part['html']);
        $this->assertStringContainsString('Hi', $part['html']);

        // No context bound -> clean error, not a crash.
        app()->forgetInstance('mw.ai.liveedit.context');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, (new \Modules\Ai\Tools\LiveEdit\GetDomTool())->__invoke());
    }

    #[Test]
    public function get_edit_fields_returns_regions_and_modules_from_context(): void
    {
        app()->instance('mw.ai.liveedit.context', [
            'dom' => '',
            'edit_fields' => [
                ['kind' => 'region', 'field' => 'content', 'rel' => 'content', 'tag' => 'div', 'id' => ''],
                ['kind' => 'module', 'id' => 'module-layouts-20', 'type' => 'layouts'],
            ],
        ]);
        $out = json_decode((new \Modules\Ai\Tools\LiveEdit\GetEditFieldsTool())->__invoke(), true);
        $this->assertSame(2, $out['count']);
        $this->assertSame('content', $out['edit_regions'][0]['field']);
        $this->assertSame('layouts', $out['modules'][0]['type']);
        app()->forgetInstance('mw.ai.liveedit.context');
    }

    #[Test]
    public function insert_layout_takes_a_template_from_get_layouts_and_rejects_empty(): void
    {
        $tool = new \Modules\Ai\Tools\LiveEdit\InsertLayoutTool();

        // A real template layout value (as returned by get_layouts).
        $ok = $tool->__invoke(template: 'content/skin-1');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);
        $this->assertStringContainsString('content/skin-1', $ok);
        // Side-effect-free frontend emitter — no persistence on the backend.
        $this->assertStringContainsString('Save', $ok);

        // No hardcoded presets — an empty template is rejected and points at get_layouts.
        $err = $tool->__invoke(template: '  ');
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $err);
        $this->assertStringContainsString('get_layouts', $err);
    }

    #[Test]
    public function get_modules_lists_insertable_module_types(): void
    {
        $tool = new \Modules\Ai\Tools\LiveEdit\GetModulesTool();

        $all = $tool->__invoke();
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $all);
        $decoded = json_decode($all, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('modules', $decoded);
        $this->assertGreaterThan(0, $decoded['count']);

        // The list is filterable by type/name and returns the real type string.
        $video = json_decode($tool->__invoke(search: 'video'), true);
        $types = array_column($video['modules'], 'module');
        $this->assertContains('video', $types);
    }

    #[Test]
    public function get_layouts_lists_template_layouts_not_hardcoded(): void
    {
        $tool = new \Modules\Ai\Tools\LiveEdit\GetLayoutsTool();

        $result = $tool->__invoke();
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $result);
        $decoded = json_decode($result, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('layouts', $decoded);
        $this->assertArrayHasKey('template', $decoded);
        // Each layout carries the `template` value insert_layout consumes.
        foreach ($decoded['layouts'] as $layout) {
            $this->assertArrayHasKey('template', $layout);
        }
    }

    #[Test]
    public function get_module_settings_requires_a_module_id(): void
    {
        $tool = new \Modules\Ai\Tools\LiveEdit\GetModuleSettingsTool();

        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $tool->__invoke(module_id: ''));

        // A missing module id reads back as an empty settings set, not an error.
        $ok = $tool->__invoke(module_id: 'no-such-module-xyz');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $ok);
        $this->assertStringContainsString('"count":0', $ok);
    }

    #[Test]
    public function add_form_field_requires_module_id_and_name(): void
    {
        $tool = new \Modules\Ai\Tools\LiveEdit\AddFormFieldTool();

        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $tool->__invoke(module_id: '', name: 'X'));
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $tool->__invoke(module_id: 'm', name: ''));
    }

    #[Test]
    public function add_form_field_creates_a_custom_field_on_the_module(): void
    {
        $moduleId = 'ai-test-resform-' . uniqid();
        $tool = new \Modules\Ai\Tools\LiveEdit\AddFormFieldTool();

        $result = $tool->__invoke(module_id: $moduleId, name: 'Number of guests', type: 'number', required: true);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $result);

        $field = \Modules\CustomFields\Models\CustomField::where('rel_type', 'module')
            ->where('rel_id', $moduleId)->first();
        $this->assertNotNull($field);
        $this->assertSame('Number of guests', $field->name);
        $this->assertSame('number', $field->type);
        $this->assertSame('number-of-guests', $field->name_key);
        $this->assertEquals(1, (int) $field->required);

        // Unknown types fall back to text (never persist an invalid type).
        $tool->__invoke(module_id: $moduleId, name: 'Weird', type: 'not-a-type');
        $weird = \Modules\CustomFields\Models\CustomField::where('rel_id', $moduleId)
            ->where('name', 'Weird')->first();
        $this->assertSame('text', $weird->type);

        \Modules\CustomFields\Models\CustomField::where('rel_type', 'module')
            ->where('rel_id', $moduleId)->delete();
    }

    #[Test]
    public function edit_menu_item_requires_an_id(): void
    {
        $tool = new \Modules\Ai\Tools\LiveEdit\EditMenuItemTool();
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, $tool->__invoke(id: 0));
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
