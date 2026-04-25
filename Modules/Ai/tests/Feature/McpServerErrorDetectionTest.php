<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Services\McpServer;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Plan C.3 — pin the new {@see McpServer::detectToolError()} logic.
 *
 * The pre-fix heuristic flipped `isError: true` whenever the tool's
 * raw output contained the literal substring `alert-danger`. That
 * produced a false positive for any read-only tool whose result
 * happened to mention the Bootstrap alert-danger class — e.g. a
 * content lookup returning a page about the Bootstrap component.
 *
 * The new contract:
 *
 *   1. The {@see BaseTool::ERROR_OUTPUT_MARKER} HTML comment is the
 *      authoritative isError signal — every tool that errors via
 *      `BaseTool::handleError()` emits it, and no normal tool output
 *      contains an HTML comment with that exact value.
 *   2. The legacy `class="alert alert-danger"` opening-tag scan is
 *      still honoured for tools that bypass BaseTool::handleError
 *      and assemble their own error markup (so the contract is
 *      backward-compatible).
 *   3. Body text mentioning `alert-danger` (e.g. a content page
 *      about Bootstrap) is NOT flagged — a regression in the
 *      detection rule that re-triggers the false-positive surfaces
 *      this test loudly.
 */
class McpServerErrorDetectionTest extends TestCase
{
    private function detectToolError(string $rawResult): bool
    {
        // detectToolError is private — invoke through reflection so
        // the contract can be exercised without going through the
        // full HTTP / fixture pipeline of McpControllerTest.
        $server = app(McpServer::class);
        $method = new ReflectionMethod($server, 'detectToolError');
        $method->setAccessible(true);
        return (bool) $method->invoke($server, $rawResult);
    }

    #[Test]
    public function output_with_explicit_error_marker_is_flagged_as_error(): void
    {
        $result = BaseTool::ERROR_OUTPUT_MARKER
            . '<div class="alert alert-danger">Validation failed.</div>';

        $this->assertTrue(
            $this->detectToolError($result),
            'Tool output containing the explicit ERROR_OUTPUT_MARKER must flag '
            . 'isError=true — that marker is the authoritative signal emitted by '
            . 'BaseTool::handleError() and is the contract every error-detection '
            . 'consumer relies on.'
        );
    }

    #[Test]
    public function output_with_alert_danger_opening_tag_falls_back_to_error_flag(): void
    {
        // Tool that bypasses BaseTool::handleError and emits its own
        // alert-danger div directly. The legacy detection path must
        // still flag this so backward compat holds.
        $result = '<div class="alert alert-danger">Old-style error.</div>';

        $this->assertTrue(
            $this->detectToolError($result),
            'A tool that emits the literal class="alert alert-danger" opening tag '
            . 'without the marker (e.g. a custom tool that does not extend '
            . 'BaseTool) must still be flagged as error — backward compat.'
        );

        $singleQuoted = "<div class='alert alert-danger'>Old-style error.</div>";
        $this->assertTrue(
            $this->detectToolError($singleQuoted),
            'Single-quoted class attribute variant must also flag — both quote '
            . 'styles are common in Microweber templates.'
        );
    }

    #[Test]
    public function output_mentioning_alert_danger_in_body_text_is_not_flagged(): void
    {
        // The pre-fix heuristic falsely flagged this; the new
        // detection requires the literal `class="alert alert-danger"`
        // opening tag so body text can safely mention the class.
        $result = 'Search Result: A page describing the alert-danger Bootstrap class.';

        $this->assertFalse(
            $this->detectToolError($result),
            'Body text that merely mentions the string `alert-danger` (e.g. a '
            . 'content lookup returning a page about Bootstrap) must NOT be '
            . 'flagged as an error — that was the false-positive the new '
            . 'detection rule was specifically introduced to eliminate.'
        );
    }

    #[Test]
    public function clean_tool_output_is_not_flagged(): void
    {
        $result = '<h1>Search Results</h1><p>Found 3 items.</p>';

        $this->assertFalse(
            $this->detectToolError($result),
            'Plain successful tool output must report isError=false — otherwise '
            . 'every tools/call would surface as an error in the calling AI side.'
        );
    }

    #[Test]
    public function base_tool_handle_error_emits_the_marker(): void
    {
        // Pin BaseTool::handleError to the marker contract so a future
        // refactor that drops the marker (e.g. someone tightening the
        // output to remove the comment for "cleanliness") fails this
        // test, not the user-facing isError signal in production.
        $tool = new class('pin-test', 'pin') extends BaseTool {
            public function __invoke(...$args): string
            {
                return $this->handleError('synthetic error');
            }
        };

        $output = $tool();
        $this->assertStringContainsString(
            BaseTool::ERROR_OUTPUT_MARKER,
            $output,
            'BaseTool::handleError must emit ERROR_OUTPUT_MARKER inline so the '
            . 'McpServer detection rule sees it. A regression here would mean '
            . 'every tool that errors via the framework helper would silently '
            . 'lose its isError=true signal in JSON-RPC responses.'
        );
    }
}
