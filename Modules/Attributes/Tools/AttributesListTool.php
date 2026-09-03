<?php

declare(strict_types=1);

namespace Modules\Attributes\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Attributes\Models\Attribute;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read content/product custom attributes.
 *
 * Exposes the Attributes module over MCP — lists attribute name/value pairs
 * (e.g. product color, size), optionally filtered to one content id or by name.
 * Read-only.
 */
class AttributesListTool extends BaseTool
{
    protected string $domain = 'attributes';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'attributes_list',
            'List content/product attributes (name, value, type, the content id '
            . 'they belong to). Optionally filter to one content id or an attribute name.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'rel_id',
                type: PropertyType::INTEGER,
                description: 'Optional content/product id to list attributes for a single item.',
                required: false,
            ),
            new ToolProperty(
                name: 'name',
                type: PropertyType::STRING,
                description: 'Optional attribute name to filter by, e.g. "color".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of attributes to return (1-200). Default 50.',
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

            $rows = Attribute::query()
                ->when(array_key_exists('rel_id', $args) && $args['rel_id'],
                    fn ($q) => $q->where('rel_id', (int) $args['rel_id']))
                ->when($name !== '', fn ($q) => $q->where('attribute_name', 'like', "%{$name}%"))
                ->orderByDesc('id')
                ->limit($limit)
                ->get(['id', 'attribute_name', 'attribute_value', 'attribute_type', 'rel_id'])
                ->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'name' => $a->attribute_name,
                        'value' => $a->attribute_value,
                        'type' => $a->attribute_type,
                        'content_id' => (int) $a->rel_id,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'attributes' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read attributes: ' . $e->getMessage());
        }
    }
}
