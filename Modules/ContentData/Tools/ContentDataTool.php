<?php

declare(strict_types=1);

namespace Modules\ContentData\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\ContentData\Models\ContentData;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the custom data (metadata) stored on a content item.
 *
 * Exposes the ContentData module over MCP — the arbitrary field_name/field_value
 * pairs saved on a page/post/product via setContentData()/setCustomField(). This
 * connects custom fields to the actual values stored per content. Read-only.
 */
class ContentDataTool extends BaseTool
{
    protected string $domain = 'contentdata';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'content_data_get',
            'Read the custom data (field_name/field_value pairs) stored on a content '
            . 'item — the metadata saved via setContentData/setCustomField. Give a '
            . 'content id (rel_id); optionally filter to one field name.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'rel_id',
                type: PropertyType::INTEGER,
                description: 'The content id to read custom data for.',
                required: true,
            ),
            new ToolProperty(
                name: 'field_name',
                type: PropertyType::STRING,
                description: 'Optional single field name to return (e.g. "author", "subtitle").',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of fields to return (1-200). Default 100.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $relId = (int) ($args['rel_id'] ?? 0);
            if ($relId <= 0) {
                return $this->handleError('A content id (rel_id) is required.');
            }
            $fieldName = trim((string) ($args['field_name'] ?? ''));
            $limit = (int) ($args['limit'] ?? 100);
            if ($limit < 1 || $limit > 200) {
                $limit = 100;
            }

            $rows = ContentData::query()
                ->where('rel_id', $relId)
                ->when($fieldName !== '', fn ($q) => $q->where('field_name', $fieldName))
                ->orderBy('field_name')
                ->limit($limit)
                ->get(['id', 'field_name', 'field_value'])
                ->map(function ($d) {
                    return [
                        'field' => $d->field_name,
                        'value' => mb_substr((string) $d->field_value, 0, 500),
                    ];
                })->all();

            return json_encode([
                'content_id' => $relId,
                'count' => count($rows),
                'data' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read content data: ' . $e->getMessage());
        }
    }
}
