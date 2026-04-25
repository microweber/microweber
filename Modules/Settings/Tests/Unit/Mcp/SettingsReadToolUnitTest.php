<?php

declare(strict_types=1);

namespace Modules\Settings\Tests\Unit\Mcp;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Tools\SettingsReadTool;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Per-module MCP tool unit test for the Settings module.
 *
 * Pre-existing MCP coverage in `Modules/Ai/tests/Feature/` exercises
 * the catalog through the HTTP MCP endpoint and asserts the
 * inventory + schemas + isError detection. This test sits next to
 * the tool's owning module (Settings) and exercises its
 * `__invoke()` directly so a regression in the tool's text output,
 * argument validation, or error-marker emission surfaces here in
 * milliseconds — without spinning up the full HTTP / token / agent
 * stack.
 *
 * Plan reference: TODO.md → "Per-module route migration + per-
 * module MCP-tool unit tests" → MTU.1.
 */
class SettingsReadToolUnitTest extends TestCase
{
    private function newTool(): SettingsReadTool
    {
        return new SettingsReadTool();
    }

    #[Test]
    public function tool_metadata_pins_the_settings_domain_and_required_perms(): void
    {
        $tool = $this->newTool();

        // The catalog binds settings.read to this tool; the
        // McpToolCatalog test ensures the inventory match. Here
        // we pin the per-tool metadata that the catalog relies
        // on so a regression on one side surfaces immediately.
        $reflection = new \ReflectionClass($tool);

        $domainProp = $reflection->getProperty('domain');
        $domainProp->setAccessible(true);
        $this->assertSame(
            'settings',
            $domainProp->getValue($tool),
            'SettingsReadTool::$domain must report "settings" — the catalog filters '
            . 'tools by this string when an MCP client narrows allowed_modules. A '
            . 'drift here would silently hide settings.read from clients with the '
            . '"settings" allow-list.'
        );

        $permsProp = $reflection->getProperty('requiredPermissions');
        $permsProp->setAccessible(true);
        $this->assertContains(
            'manage_settings',
            $permsProp->getValue($tool),
            'SettingsReadTool::$requiredPermissions must include manage_settings — '
            . 'the BaseTool::authorize() gate consults this when deciding whether to '
            . 'flip the response into the unauthorized-error branch.'
        );
    }

    #[Test]
    public function missing_option_group_returns_the_documented_error(): void
    {
        $tool = $this->newTool();

        // Empty option_group must produce a BaseTool error response.
        // The exact message text is the operator-visible signal,
        // and the marker is what McpServer reads to flip isError.
        $result = $tool('option_group', '');

        $this->assertStringContainsString(
            BaseTool::ERROR_OUTPUT_MARKER,
            $result,
            'Empty option_group must trigger BaseTool::handleError so McpServer '
            . 'flips isError=true. A regression that returns a plain text response '
            . 'would silently hide the validation failure from AI clients.'
        );
        $this->assertStringContainsString(
            'Option group is required',
            $result,
            'Error message text is the operator-visible signal — pin the canonical '
            . 'phrasing so a copy-edit doesn\'t silently change what AI clients see.'
        );
    }

    #[Test]
    public function tool_properties_carry_the_documented_input_schema(): void
    {
        // The tool exposes properties() via the parent BaseTool.
        // McpToolCatalog reflects on these to build the JSON
        // schema. Pin the three documented fields (option_group,
        // option_key, limit) and their required flags so a future
        // refactor can't silently drop one without surfacing here.
        $tool = $this->newTool();

        $properties = $tool->getProperties();
        $byName = [];
        foreach ($properties as $property) {
            $byName[$property->getName()] = $property;
        }

        $this->assertArrayHasKey('option_group', $byName);
        $this->assertArrayHasKey('option_key', $byName);
        $this->assertArrayHasKey('limit', $byName);

        $this->assertTrue(
            $byName['option_group']->isRequired(),
            'option_group must be marked required — the JSON schema relies on this '
            . 'flag to validate AI client requests up-front instead of letting them '
            . 'land as runtime errors.'
        );
        $this->assertFalse(
            $byName['option_key']->isRequired(),
            'option_key must be optional so listing-mode (group dump) keeps working.'
        );
    }
}
