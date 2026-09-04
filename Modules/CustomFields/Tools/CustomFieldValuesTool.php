<?php

declare(strict_types=1);

namespace Modules\CustomFields\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\CustomFields\Models\CustomField;
use Modules\CustomFields\Models\CustomFieldValue;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the option values of a custom field definition.
 *
 * Connects a custom field (from custom_fields_list) to its stored option values
 * (e.g. a dropdown/checkbox list's options, with any price modifier). Read-only.
 */
class CustomFieldValuesTool extends BaseTool
{
    protected string $domain = 'customfields';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'custom_field_values',
            'Read the option values of a custom field definition (e.g. a dropdown or '
            . 'checkbox list\'s options, with any price modifier). Provide the '
            . 'custom_field_id from custom_fields_list.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'custom_field_id',
                type: PropertyType::INTEGER,
                description: 'The id of the custom field definition (from custom_fields_list).',
                required: true,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of values to return (1-200). Default 100.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $fieldId = (int) ($args['custom_field_id'] ?? 0);
            if ($fieldId <= 0) {
                return $this->handleError('A custom_field_id is required.');
            }
            $limit = (int) ($args['limit'] ?? 100);
            if ($limit < 1 || $limit > 200) {
                $limit = 100;
            }

            $field = CustomField::query()->find($fieldId, ['id', 'name', 'name_key', 'type']);

            $rows = CustomFieldValue::query()
                ->where('custom_field_id', $fieldId)
                ->orderBy('position')
                ->limit($limit)
                ->get(['id', 'value', 'price_modifier', 'position'])
                ->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'value' => $v->value,
                        'price_modifier' => $v->price_modifier !== null ? (float) $v->price_modifier : null,
                    ];
                })->all();

            return json_encode([
                'custom_field_id' => $fieldId,
                'field' => $field ? ['name' => $field->name, 'key' => $field->name_key, 'type' => $field->type] : null,
                'count' => count($rows),
                'values' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read custom field values: ' . $e->getMessage());
        }
    }
}
