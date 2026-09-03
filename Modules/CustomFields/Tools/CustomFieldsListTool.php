<?php

declare(strict_types=1);

namespace Modules\CustomFields\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\CustomFields\Models\CustomField;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read custom field definitions.
 *
 * Exposes the CustomFields module over MCP — lists custom field definitions
 * (name, type, required, the content they belong to), optionally filtered to
 * one content id or by name. Read-only.
 */
class CustomFieldsListTool extends BaseTool
{
    protected string $domain = 'customfields';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'custom_fields_list',
            'List custom field definitions (name, type, required, active, the '
            . 'content id they belong to). Optionally filter to one content id or a name.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'rel_id',
                type: PropertyType::INTEGER,
                description: 'Optional content id to list custom fields for a single item.',
                required: false,
            ),
            new ToolProperty(
                name: 'name',
                type: PropertyType::STRING,
                description: 'Optional field name to filter by.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of fields to return (1-200). Default 50.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $name = trim((string) ($args['name'] ?? ''));
            $limit = (int) ($args['limit'] ?? 50);
            if ($limit < 1 || $limit > 200) {
                $limit = 50;
            }

            $rows = CustomField::query()
                ->when(array_key_exists('rel_id', $args) && $args['rel_id'],
                    fn ($q) => $q->where('rel_id', (int) $args['rel_id']))
                ->when($name !== '', fn ($q) => $q->where('name', 'like', "%{$name}%"))
                ->orderBy('position')
                ->limit($limit)
                ->get(['id', 'name', 'name_key', 'type', 'rel_id', 'required', 'is_active'])
                ->map(function ($f) {
                    return [
                        'id' => $f->id,
                        'name' => $f->name,
                        'key' => $f->name_key,
                        'type' => $f->type,
                        'content_id' => (int) $f->rel_id,
                        'required' => (int) $f->required === 1,
                        'active' => (int) $f->is_active === 1,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'fields' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read custom fields: ' . $e->getMessage());
        }
    }
}
