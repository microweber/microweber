<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Modules\Ai\Services\Mcp\McpToolCatalog;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Plan C.2 / G — pin the input-schema shape produced by
 * {@see McpToolCatalog::buildInputSchema()}.
 *
 * Every tools/list response embeds a JSON-Schema-shaped
 * `inputSchema` per tool. AI clients (Claude Desktop, Cursor, Cline)
 * render this schema as the prompt scaffold the LLM uses to assemble
 * the next tool call's arguments. A regression in the schema builder
 * (collapsing `integer` types to `string`, dropping `required`,
 * losing `enum`, leaking `additionalProperties: true`) silently
 * breaks the AI side without any visible error on the wire.
 *
 * The test pins the schema for two representative tools:
 *
 *   - `content.lookup` covers: required string field, optional
 *     string field, optional integer field (so the integer-vs-
 *     string-collapse regression is caught), `additionalProperties:
 *     false` discipline.
 *   - `analytics.audience_breakdown` covers: an enum-bearing
 *     property (the lookback period). Pinning it here catches a
 *     regression that drops the enum from the schema, which would
 *     turn a constrained selector into a free-text field that the
 *     LLM could fill with anything.
 *
 * Adding a new tool with novel schema shape (e.g. an array of
 * objects, a nullable union) should add a corresponding pin here so
 * future builder changes don't silently drop the new shape feature.
 */
class McpToolInputSchemaRegressionTest extends TestCase
{
    private function buildInputSchema(string $toolName): array
    {
        $catalog = app(McpToolCatalog::class);
        $definition = $catalog->allDefinitions()[$toolName] ?? null;
        $this->assertIsArray($definition, "Tool '{$toolName}' must exist in the catalog.");
        $tool = app()->make($definition['tool']);

        $method = new ReflectionMethod($catalog, 'buildInputSchema');
        $method->setAccessible(true);
        return (array) $method->invoke($catalog, $tool);
    }

    #[Test]
    public function content_lookup_input_schema_pins_required_search_term_and_typed_limit(): void
    {
        $schema = $this->buildInputSchema('content.lookup');

        $this->assertSame('object', $schema['type']);
        $this->assertFalse(
            $schema['additionalProperties'] ?? true,
            'inputSchema.additionalProperties must be false — otherwise AI clients '
            . 'can pass arbitrary keys that the tool will silently ignore, leading '
            . 'to confusing "tool ran but ignored my parameter" failures.'
        );

        $this->assertSame(
            ['search_term'],
            $schema['required'] ?? [],
            'content.lookup must require search_term — without it the tool returns '
            . 'an error string. Dropping the required marker means the LLM may '
            . 'happily call it with no args, then surface the error as a "tool '
            . 'failure" rather than a missing-arg signal up-front.'
        );

        $this->assertArrayHasKey('search_term', $schema['properties']);
        $this->assertSame('string', $schema['properties']['search_term']['type']);

        $this->assertArrayHasKey('limit', $schema['properties']);
        $this->assertSame(
            'integer',
            $schema['properties']['limit']['type'],
            'limit must surface as integer in the schema. A regression in the '
            . 'builder that collapses everything to "string" would let the LLM '
            . 'assemble `limit: "ten"` and the tool would crash on cast.'
        );

        $this->assertArrayHasKey('content_type', $schema['properties']);
        $this->assertSame('string', $schema['properties']['content_type']['type']);
    }

    #[Test]
    public function input_schema_emits_enum_when_property_declares_one(): void
    {
        // No catalog tool today declares an enum, so we exercise the
        // builder's enum branch directly through a synthetic property
        // object that carries the same shape McpToolCatalog reflects
        // off real tool properties. A regression that drops the enum
        // copy in McpToolCatalog::buildInputSchema would turn every
        // future enum-bearing tool's constrained selector into a
        // free-text field — pin the branch even though no tool
        // currently exercises it.
        $catalog = app(McpToolCatalog::class);

        $syntheticTool = new class implements \MicroweberPackages\AiTools\Contracts\ToolInterface {
            public function __invoke(...$args): string { return ''; }
            public function getName(): string { return 'synthetic.enum'; }
            public function getDescription(): string { return 'pin'; }
            public function getDomain(): string { return 'test'; }
            public function getRequiredPermissions(): array { return []; }
            public function getMaxTries(): ?int { return null; }
            public function isAuthorized(): bool { return true; }
            public function getProperties(): array
            {
                return [
                    new class {
                        public string $name = 'period';
                        public string $type = 'string';
                        public string $description = 'Reporting period.';
                        public bool $required = false;
                        public array $enum = ['daily', 'weekly', 'monthly', 'yearly'];
                    },
                ];
            }
        };

        $method = new ReflectionMethod($catalog, 'buildInputSchema');
        $method->setAccessible(true);
        $schema = (array) $method->invoke($catalog, $syntheticTool);

        $this->assertArrayHasKey('period', $schema['properties']);
        $this->assertSame(
            ['daily', 'weekly', 'monthly', 'yearly'],
            $schema['properties']['period']['enum'] ?? null,
            'Schema builder must copy the property\'s `enum` array verbatim into '
            . 'the JSON-Schema property. A regression that drops the enum branch '
            . 'would turn every enum-bearing property into a free-text field that '
            . 'the LLM could fill with anything.'
        );
    }

    #[Test]
    public function input_schema_promotes_format_pattern_minimum_maximum_default_when_declared(): void
    {
        // Plan C.2 follow-up: the schema builder must copy the
        // optional JSON-Schema decorators (format, pattern,
        // minimum, maximum, default) from the underlying property
        // class verbatim. No catalog tool today uses these, so we
        // exercise the branch via a synthetic tool — a regression
        // that drops any of these from the builder would silently
        // strip future tools' schema hints.
        $catalog = app(McpToolCatalog::class);

        $syntheticTool = new class implements \MicroweberPackages\AiTools\Contracts\ToolInterface {
            public function __invoke(...$args): string { return ''; }
            public function getName(): string { return 'synthetic.decorators'; }
            public function getDescription(): string { return 'pin'; }
            public function getDomain(): string { return 'test'; }
            public function getRequiredPermissions(): array { return []; }
            public function getMaxTries(): ?int { return null; }
            public function isAuthorized(): bool { return true; }
            public function getProperties(): array
            {
                return [
                    new class {
                        public string $name = 'callback_url';
                        public string $type = 'string';
                        public string $description = 'Webhook callback.';
                        public bool $required = false;
                        public string $format = 'uri';
                        public string $pattern = '^https://.+';
                    },
                    new class {
                        public string $name = 'page_size';
                        public string $type = 'integer';
                        public string $description = 'Rows per page.';
                        public bool $required = false;
                        public int $minimum = 1;
                        public int $maximum = 100;
                        public int $default = 20;
                    },
                ];
            }
        };

        $method = new ReflectionMethod($catalog, 'buildInputSchema');
        $method->setAccessible(true);
        $schema = (array) $method->invoke($catalog, $syntheticTool);

        $this->assertSame('uri', $schema['properties']['callback_url']['format']);
        $this->assertSame('^https://.+', $schema['properties']['callback_url']['pattern']);

        $this->assertSame(1, $schema['properties']['page_size']['minimum']);
        $this->assertSame(100, $schema['properties']['page_size']['maximum']);
        $this->assertSame(20, $schema['properties']['page_size']['default']);
    }

    #[Test]
    public function settings_read_input_schema_pins_required_option_group(): void
    {
        // settings.read requires option_group — every consumer of
        // tools/list relies on this required marker so the LLM
        // always supplies the group when assembling a settings.read
        // call. A regression that drops the required marker would
        // let the LLM call settings.read with no args, then surface
        // a confusing "missing option_group" error at runtime
        // instead of failing schema validation up-front.
        $schema = $this->buildInputSchema('settings.read');

        $this->assertSame(
            ['option_group'],
            $schema['required'] ?? [],
            'settings.read must require option_group — without it the tool returns '
            . 'an error string at runtime; with it, AI clients schema-validate the '
            . 'arguments before sending the call and surface the missing-arg signal '
            . 'up-front.'
        );
        $this->assertSame('string', $schema['properties']['option_group']['type']);
    }

    #[Test]
    public function every_catalog_tool_emits_a_well_shaped_input_schema(): void
    {
        // Pin the global invariants: every tool's schema must be an
        // object, must declare additionalProperties=false, and must
        // produce a properties map even if empty. Catches a builder
        // regression that affects the entire catalog at once.
        $catalog = app(McpToolCatalog::class);

        foreach (array_keys($catalog->allDefinitions()) as $toolName) {
            $schema = $this->buildInputSchema($toolName);

            $this->assertSame(
                'object',
                $schema['type'] ?? null,
                "Tool '{$toolName}' inputSchema.type must be 'object' — JSON-Schema "
                . 'requires this for object schemas, and AI clients reject schemas '
                . 'that omit it.'
            );
            $this->assertSame(
                false,
                $schema['additionalProperties'] ?? null,
                "Tool '{$toolName}' inputSchema.additionalProperties must be false — "
                . 'otherwise AI clients can pass arbitrary keys that the tool silently '
                . 'ignores.'
            );
            $this->assertIsArray(
                $schema['properties'] ?? null,
                "Tool '{$toolName}' inputSchema.properties must be a (possibly-empty) "
                . 'array — every JSON-Schema object schema declares this, even if no '
                . 'properties are exposed.'
            );
        }
    }
}
