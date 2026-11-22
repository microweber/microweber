<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for the custom-JS layer — the JS sibling of the Live-Edit
 * CSS layer. Per-template userfiles/css/<Template>/live_edit.js, saved by the
 * manager / the AI apply_js tool, served as a deferred <script> in the PUBLISHED
 * footer only (never inside the editor canvas). This pins the wiring across all
 * pieces so none silently regresses. Source-based (pre-boot) like the sibling
 * LiveEditGuardsContractTest.
 */
final class LiveEditCustomJsLayerContractTest extends TestCase
{
    private function root(): string
    {
        // Modules/Ai/Tests/Contract -> repo root is four levels up.
        return dirname(__DIR__, 4);
    }

    private function read(string $rel): string
    {
        $p = $this->root() . '/' . $rel;
        $this->assertFileExists($p, "$rel is missing.");

        return (string) file_get_contents($p);
    }

    #[Test]
    public function config_declares_live_edit_js_file_type(): void
    {
        $src = $this->read('packages/microweber-template-custom-css/config/template-custom-css.php');
        $this->assertStringContainsString("'live_edit_js'", $src, 'A live_edit_js file type must be declared.');
        $this->assertMatchesRegularExpression("~'filename'\s*=>\s*'live_edit\.js'~", $src);
        $this->assertMatchesRegularExpression("~'option_key'\s*=>\s*'template_js'~", $src);
    }

    #[Test]
    public function manager_exposes_js_methods(): void
    {
        $src = $this->read('packages/microweber-template-custom-css/src/Services/LiveEditCssManager.php');
        foreach ([
            'function getLiveEditJsUrl',
            'function saveLiveEditJsContent',
            'function getLiveEditJsContent',
            'function getLiveEditJsPath',
            'function getLiveEditJsFilename',
        ] as $m) {
            $this->assertStringContainsString($m, $src, "LiveEditCssManager must expose $m.");
        }
        // Empty file must yield a null URL so no empty <script> is ever shipped.
        $this->assertStringContainsString('filesize($path) === 0', $src, 'getLiveEditJsUrl must return null for an empty file.');
    }

    #[Test]
    public function footer_entity_gates_off_in_editor_and_emits_deferred_script(): void
    {
        $src = $this->read('packages/microweber-meta-tags/src/Entities/LiveEditJsFooterTags.php');
        $this->assertMatchesRegularExpression('~is_live_edit\(\)~', $src, 'Custom JS must be gated OFF inside the Live-Edit editor (is_live_edit()).');
        $this->assertStringContainsString('getLiveEditJsUrl', $src, 'The footer entity must resolve the JS URL via the manager.');
        $this->assertStringContainsString('id="mw-template-custom-js"', $src);
        $this->assertStringContainsString('defer', $src, 'The custom JS <script> must be deferred.');
        $this->assertStringContainsString('PLACEMENT_FOOTER', $src, 'Custom JS must load in the footer.');
    }

    #[Test]
    public function footer_entity_is_registered_in_meta_tags_provider(): void
    {
        $src = $this->read('packages/microweber-meta-tags/src/Providers/MetaTagsServiceProvider.php');
        $this->assertStringContainsString('LiveEditJsFooterTags', $src, 'The JS footer tag must be registered.');
    }

    #[Test]
    public function apply_js_tool_persists_server_side_and_strips_script_tags(): void
    {
        $src = $this->read('Modules/Ai/Tools/LiveEdit/ApplyJsTool.php');
        $this->assertStringContainsString("'apply_js'", $src, 'The tool must be named apply_js.');
        $this->assertStringContainsString('saveLiveEditJsContent', $src, 'apply_js must persist via the manager (no live-canvas apply for JS).');
        // Strips any <script> wrapper the model adds.
        $this->assertStringContainsString('</?script', $src, 'apply_js must strip <script> wrappers.');
    }

    #[Test]
    public function apply_js_tool_registered_and_documented_in_agent(): void
    {
        $src = $this->read('Modules/Ai/Agents/LiveEditAgent.php');
        $this->assertStringContainsString('use Modules\Ai\Tools\LiveEdit\ApplyJsTool;', $src, 'ApplyJsTool must be imported.');
        $this->assertStringContainsString('new ApplyJsTool(', $src, 'ApplyJsTool must be registered in setupTools().');
        $this->assertStringContainsString('ADD INTERACTIVITY + MOTION WITH apply_js', $src, 'The prompt must tell the agent how/when to use apply_js.');
    }

    #[Test]
    public function replace_js_tool_is_the_surgical_js_edit_sibling_of_replace_css(): void
    {
        // The JS twin of replace_css: find/replace inside the persisted site JS so
        // the agent can change/remove JS without re-sending it (apply_js only appends).
        $src = $this->read('Modules/Ai/Tools/LiveEdit/ReplaceJsTool.php');
        $this->assertStringContainsString("'replace_js'", $src, 'The tool must be named replace_js.');
        $this->assertStringContainsString('getLiveEditJsContent', $src, 'replace_js must read the current site JS.');
        $this->assertStringContainsString('saveLiveEditJsContent', $src, 'replace_js must persist via the manager (no live-canvas JS).');
        $this->assertStringContainsString('str_replace', $src, 'replace_js must perform a find/replace.');
        $this->assertStringContainsString('</?script', $src, 'replace_js must strip <script> wrappers from the replacement.');
    }

    #[Test]
    public function replace_js_tool_registered_in_agent(): void
    {
        $src = $this->read('Modules/Ai/Agents/LiveEditAgent.php');
        $this->assertStringContainsString('use Modules\Ai\Tools\LiveEdit\ReplaceJsTool;', $src, 'ReplaceJsTool must be imported.');
        $this->assertStringContainsString('new ReplaceJsTool(', $src, 'ReplaceJsTool must be registered in setupTools().');
    }

    #[Test]
    public function get_page_context_exposes_current_custom_js_for_replace_js(): void
    {
        // replace_js tells the model to read the snippet from get_page_context; that
        // tool must actually return the site JS, or the find/replace has nothing to copy.
        $src = $this->read('Modules/Ai/Tools/LiveEdit/GetPageContextTool.php');
        $this->assertStringContainsString('current_custom_js', $src, 'get_page_context must expose the site custom JS.');
        $this->assertStringContainsString('getLiveEditJsContent', $src, 'get_page_context must read the JS via the manager.');
    }

    #[Test]
    public function inline_script_routing_trait_extracts_and_persists_js(): void
    {
        // The shared trait is the single place that extracts a <script> from
        // model-authored HTML and persists its JS to the site custom-JS layer, so
        // add_section and edit_html can never drift apart.
        $src = $this->read('Modules/Ai/Tools/LiveEdit/Concerns/RoutesInlineJsToSiteJs.php');
        $this->assertStringContainsString('preg_replace_callback', $src, 'The trait must extract <script> blocks from the HTML.');
        $this->assertStringContainsString('saveLiveEditJsContent', $src, 'The trait must persist extracted JS to the custom-JS layer.');
        $this->assertStringContainsString('stripAndCollectInlineScripts', $src, 'The trait must expose the strip/collect helper.');
        $this->assertStringContainsString('routeSectionJsToSiteJs', $src, 'The trait must expose the persist helper.');
    }

    #[Test]
    public function add_section_routes_inline_script_to_the_site_js_layer(): void
    {
        // An inline <script> inside a section is stripped on the canvas (lost) and a
        // half-streamed <script> chunk corrupts the markup. add_section must use the
        // shared routing trait to persist the JS and strip the tags from the HTML.
        $src = $this->read('Modules/Ai/Tools/LiveEdit/AddSectionTool.php');
        $this->assertStringContainsString('use RoutesInlineJsToSiteJs;', $src, 'add_section must use the inline-JS routing trait.');
        $this->assertStringContainsString('stripAndCollectInlineScripts', $src, 'add_section must strip/collect inline scripts.');
        $this->assertStringContainsString('routeSectionJsToSiteJs', $src, 'add_section must route extracted JS.');

        // The frontend canvas handler must also strip <script> so a corrupt chunk
        // cannot leak as visible text.
        $js = $this->read('Modules/Ai/resources/assets/js/mw-ai.js');
        $this->assertStringContainsString('<\/script\s*>/gi', $js,
            'mw-ai.js add_section must strip <script> blocks from section HTML.');
    }

    #[Test]
    public function edit_html_routes_inline_script_to_the_site_js_layer(): void
    {
        // edit_html writes into an element's innerHTML, which persists in content_body
        // and would run a <script> on the published page — the same leak. It must share
        // the routing trait, and the frontend handler must strip <script> from replace.
        $src = $this->read('Modules/Ai/Tools/LiveEdit/EditHtmlTool.php');
        $this->assertStringContainsString('use RoutesInlineJsToSiteJs;', $src, 'edit_html must use the inline-JS routing trait.');
        $this->assertStringContainsString('routeSectionJsToSiteJs', $src, 'edit_html must route an inline <script> to the custom-JS layer.');

        $js = $this->read('Modules/Ai/resources/assets/js/mw-ai.js');
        // The edit_html frontend handler strips <script> from the replacement HTML.
        $this->assertStringContainsString('Never write a <script> into content HTML', $js,
            'mw-ai.js edit_html must strip <script> blocks from the replacement HTML.');
    }
}
