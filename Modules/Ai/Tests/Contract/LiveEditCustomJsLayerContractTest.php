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
}
