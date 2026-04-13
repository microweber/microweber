<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\Option\Models\Option;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class SettingsReadTool extends BaseTool
{
    protected string $domain = 'settings';
    protected array $requiredPermissions = ['manage_settings'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'settings_read',
            'Read non-sensitive Microweber settings by option group and optional option key.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'option_group',
                type: PropertyType::STRING,
                description: 'The Microweber option group to read, for example "website", "ai", or "template".',
                required: true,
            ),
            new ToolProperty(
                name: 'option_key',
                type: PropertyType::STRING,
                description: 'Optional specific option key to read. If omitted, the tool lists non-sensitive options in the group.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of option rows to return when listing a group (default: 20, max: 50).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $optionGroup = trim((string) ($args['option_group'] ?? ''));
        $optionKey = trim((string) ($args['option_key'] ?? ''));
        $limit = max(1, min(50, (int) ($args['limit'] ?? 20)));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to read settings.');
        }

        if ($optionGroup === '') {
            return $this->handleError('Option group is required.');
        }

        if ($optionKey !== '' && $this->isSensitiveOption($optionKey)) {
            return $this->handleError("The setting '{$optionKey}' is sensitive and cannot be read over MCP.");
        }

        if ($optionKey !== '') {
            $value = get_option($optionKey, $optionGroup);

            if ($value === false || $value === null || $value === '') {
                return $this->handleError("No setting found for {$optionGroup}.{$optionKey}.");
            }

            return $this->formatAsHtmlTable(
                [[
                    'group' => $optionGroup,
                    'key' => $optionKey,
                    'value' => $this->normalizeValue($value),
                ]],
                [
                    'group' => 'Group',
                    'key' => 'Key',
                    'value' => 'Value',
                ],
                'No settings found.',
                'settings-read-result'
            );
        }

        $options = Option::query()
            ->where('option_group', $optionGroup)
            ->orderBy('option_key')
            ->limit($limit)
            ->get()
            ->reject(fn (Option $option): bool => $this->isSensitiveOption((string) $option->option_key))
            ->map(fn (Option $option): array => [
                'key' => $option->option_key,
                'value' => $this->normalizeValue($option->option_value),
            ])
            ->values()
            ->all();

        if ($options === []) {
            return $this->handleError("No non-sensitive settings found in option group '{$optionGroup}'.");
        }

        return $this->formatAsHtmlTable(
            $options,
            [
                'key' => 'Key',
                'value' => 'Value',
            ],
            'No settings found.',
            'settings-read-list'
        );
    }

    private function isSensitiveOption(string $optionKey): bool
    {
        return (bool) preg_match('/(password|secret|token|api[_-]?key|client[_-]?secret|private[_-]?key)/i', $optionKey);
    }

    private function normalizeValue(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
