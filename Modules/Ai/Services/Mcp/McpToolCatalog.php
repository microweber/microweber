<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Modules\Ai\Tools\ContentSearchTool;
use Modules\Ai\Tools\GetContentTool;
use Modules\Ai\Tools\NewsletterAutomationStatusTool;
use Modules\Ai\Tools\NewsletterCampaignLookupTool;
use Modules\Ai\Tools\NewsletterSubscriberLookupTool;
use Modules\Ai\Tools\NewsletterTemplateLookupTool;
use Modules\Ai\Tools\OrderSearchTool;
use Modules\Ai\Tools\ProductSearchTool;
use Modules\Ai\Tools\SettingsReadTool;
use MicroweberPackages\AiTools\Contracts\ToolInterface;
use ReflectionClass;

class McpToolCatalog
{
    /**
     * @return array<string, array{tool: class-string<ToolInterface>, module: string, title: string}>
     */
    public function allDefinitions(): array
    {
        return [
            'content.lookup' => [
                'tool' => ContentSearchTool::class,
                'module' => 'content',
                'title' => 'Search Microweber content by keyword and type.',
            ],
            'content.get' => [
                'tool' => GetContentTool::class,
                'module' => 'content',
                'title' => 'Retrieve detailed information for a Microweber content item by ID.',
            ],
            'product.lookup' => [
                'tool' => ProductSearchTool::class,
                'module' => 'product',
                'title' => 'Search Microweber products by title, category, or price range.',
            ],
            'order.lookup' => [
                'tool' => OrderSearchTool::class,
                'module' => 'order',
                'title' => 'Search Microweber orders by reference, customer, status, or date.',
            ],
            'settings.read' => [
                'tool' => SettingsReadTool::class,
                'module' => 'settings',
                'title' => 'Read non-sensitive Microweber option values by group and key.',
            ],
            'newsletter.campaign_lookup' => [
                'tool' => NewsletterCampaignLookupTool::class,
                'module' => 'newsletter',
                'title' => 'Search newsletter campaigns by name, status, type, and engagement summary.',
            ],
            'newsletter.subscriber_lookup' => [
                'tool' => NewsletterSubscriberLookupTool::class,
                'module' => 'newsletter',
                'title' => 'Search newsletter subscribers with masked email output and list membership details.',
            ],
            'newsletter.template_lookup' => [
                'tool' => NewsletterTemplateLookupTool::class,
                'module' => 'newsletter',
                'title' => 'Search newsletter templates and review their campaign usage.',
            ],
            'newsletter.automation_status' => [
                'tool' => NewsletterAutomationStatusTool::class,
                'module' => 'newsletter',
                'title' => 'Review newsletter automation queue health and recent workflow execution status.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listTools(McpRequestContext $context): array
    {
        $tools = [];

        foreach ($this->allDefinitions() as $mcpToolName => $definition) {
            if (! $context->client->allowsTool($mcpToolName) || ! $context->client->allowsModule($definition['module'])) {
                continue;
            }

            if ($this->requiresAdminScope($mcpToolName, $definition['module']) && ! $context->token->allowsScope((string) config('modules.ai.mcp.auth.admin_scope', 'mcp:admin'))) {
                continue;
            }

            $tool = app()->make($definition['tool']);

            $tools[] = [
                'name' => $mcpToolName,
                'description' => $definition['title'] ?: $tool->getDescription(),
                'inputSchema' => $this->buildInputSchema($tool),
                'annotations' => [
                    'module' => $definition['module'],
                    'domain' => $tool->getDomain(),
                    'readOnlyHint' => true,
                ],
            ];
        }

        return $tools;
    }

    public function hasTool(string $name): bool
    {
        return array_key_exists($name, $this->allDefinitions());
    }

    public function callTool(string $name, array $arguments): string
    {
        $definition = $this->allDefinitions()[$name] ?? null;

        if ($definition === null) {
            throw new \InvalidArgumentException("MCP tool [{$name}] is not registered.");
        }

        /** @var ToolInterface $tool */
        $tool = app()->make($definition['tool']);

        return (string) $tool(...$arguments);
    }

    private function buildInputSchema(ToolInterface $tool): array
    {
        $properties = [];
        $required = [];

        foreach ($tool->getProperties() as $property) {
            $propertyData = $this->extractToolPropertyData($property);
            $propertyName = (string) ($propertyData['name'] ?? '');

            if ($propertyName === '') {
                continue;
            }

            $schema = [
                'type' => $propertyData['type'] ?? 'string',
                'description' => $propertyData['description'] ?? '',
            ];

            if (! empty($propertyData['enum'])) {
                $schema['enum'] = $propertyData['enum'];
            }

            $properties[$propertyName] = $schema;

            if (($propertyData['required'] ?? false) === true) {
                $required[] = $propertyName;
            }
        }

        $schema = [
            'type' => 'object',
            'properties' => $properties,
            'additionalProperties' => false,
        ];

        if ($required !== []) {
            $schema['required'] = $required;
        }

        return $schema;
    }

    /**
     * @return array{name?: string, type?: string, description?: string, required?: bool, enum?: array<int, mixed>}
     */
    private function extractToolPropertyData(object $property): array
    {
        $reflection = new ReflectionClass($property);
        $data = [];

        foreach (['name', 'type', 'description', 'required', 'enum'] as $field) {
            if (! $reflection->hasProperty($field)) {
                continue;
            }

            $reflectionProperty = $reflection->getProperty($field);
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($property);

            if ($field === 'type' && $value instanceof \UnitEnum) {
                $value = $value->value;
            }

            $data[$field] = $value;
        }

        return $data;
    }

    private function requiresAdminScope(string $toolName, string $moduleName): bool
    {
        $adminOnlyTools = (array) config('modules.ai.mcp.auth.admin_only_tools', []);
        $adminOnlyModules = (array) config('modules.ai.mcp.auth.admin_only_modules', []);

        return in_array($toolName, $adminOnlyTools, true)
            || in_array($moduleName, $adminOnlyModules, true);
    }
}
