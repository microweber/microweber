<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for the Live-Edit "diff" editing tools — edit_html and
 * replace_css — which let the agent change PART of existing content/CSS via a
 * find/replace instead of re-emitting the whole block. Each is a side-effect-free
 * backend tool (schema + echo) plus a frontendTools handler in mw-ai.js that does
 * the find/replace on the live canvas. Pins the wiring across both halves.
 */
final class LiveEditDiffToolsContractTest extends TestCase
{
    private function root(): string
    {
        return dirname(__DIR__, 4);
    }

    private function read(string $rel): string
    {
        $p = $this->root() . '/' . $rel;
        $this->assertFileExists($p, "$rel is missing.");

        return (string) file_get_contents($p);
    }

    #[Test]
    public function backend_tools_exist_and_are_side_effect_free(): void
    {
        $eh = $this->read('Modules/Ai/Tools/LiveEdit/EditHtmlTool.php');
        $this->assertStringContainsString("'edit_html'", $eh);
        foreach (["'selector'", "'find'", "'replace'"] as $prop) {
            $this->assertStringContainsString($prop, $eh, "edit_html must declare the $prop property.");
        }
        // Side-effect-free: the front-end applies it (no server-side DOM write here).
        $this->assertStringContainsString('will be applied to', $eh);

        $rc = $this->read('Modules/Ai/Tools/LiveEdit/ReplaceCssTool.php');
        $this->assertStringContainsString("'replace_css'", $rc);
        $this->assertStringContainsString("'find'", $rc);
        $this->assertStringContainsString("'replace'", $rc);
    }

    #[Test]
    public function tools_registered_and_prompted_in_agent(): void
    {
        $src = $this->read('Modules/Ai/Agents/LiveEditAgent.php');
        $this->assertStringContainsString('use Modules\Ai\Tools\LiveEdit\EditHtmlTool;', $src);
        $this->assertStringContainsString('use Modules\Ai\Tools\LiveEdit\ReplaceCssTool;', $src);
        $this->assertStringContainsString('new EditHtmlTool(', $src);
        $this->assertStringContainsString('new ReplaceCssTool(', $src);
        $this->assertStringContainsString('EDIT SURGICALLY', $src, 'The prompt must tell the agent to edit surgically with edit_html/replace_css.');
    }

    #[Test]
    public function frontend_mw_ai_js_has_diff_handlers(): void
    {
        // The source file is tracked; the public copy is a gitignored deploy artifact.
        $src = $this->read('Modules/Ai/resources/assets/js/mw-ai.js');
        $this->assertMatchesRegularExpression('~edit_html:\s*function~', $src, 'mw-ai.js must implement the edit_html frontend handler.');
        $this->assertMatchesRegularExpression('~replace_css:\s*function~', $src, 'mw-ai.js must implement the replace_css frontend handler.');
        // edit_html must register the content change so SAVE persists it.
        $pos = strpos($src, 'edit_html: function');
        $this->assertNotFalse($pos);
        $this->assertStringContainsString('registerChangedState', substr($src, (int) $pos, 1600), 'edit_html must register the changed state for SAVE.');
    }

    #[Test]
    public function public_copy_in_sync_when_present(): void
    {
        $pub = $this->root() . '/public/modules/ai/js/mw-ai.js';
        if (!is_file($pub)) {
            $this->markTestSkipped('public mw-ai.js not present (gitignored; republished on deploy).');
        }
        $body = (string) file_get_contents($pub);
        $this->assertMatchesRegularExpression('~edit_html:\s*function~', $body, 'The deployed mw-ai.js must be re-copied after editing source (it lacks edit_html).');
        $this->assertMatchesRegularExpression('~replace_css:\s*function~', $body, 'The deployed mw-ai.js must be re-copied after editing source (it lacks replace_css).');
    }
}
