<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: set a custom field / content-data value on a content item.
 *
 * Writes a field_name/field_value onto a page/post/PRODUCT via the content-data
 * store (setContentData) — e.g. a product's sku, qty, or any custom field. Unlike
 * the canvas tools this performs a real server-side write, so the AI can edit
 * product custom fields from the chat box. Pair with the frontend module reload
 * (mw-ai.js) so the change shows on the canvas without a full page refresh.
 */
class SetCustomFieldTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_custom_field',
            'Set a custom field (content-data) value on a content item — a page, '
            . 'post or PRODUCT. Use this to edit product custom fields such as sku, '
            . 'qty, brand, or any custom key. Provide the content_id, the field name '
            . 'and the value.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'The id of the content/product to set the field on.',
                required: true,
            ),
            new ToolProperty(
                name: 'field',
                type: PropertyType::STRING,
                description: 'The custom field name/key, e.g. "sku", "qty", "brand".',
                required: true,
            ),
            new ToolProperty(
                name: 'value',
                type: PropertyType::STRING,
                description: 'The value to store for the field.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $contentId = (int) ($args['content_id'] ?? 0);
        $field = trim((string) ($args['field'] ?? ''));
        $value = (string) ($args['value'] ?? '');

        if ($contentId <= 0 || $field === '') {
            return $this->handleError('content_id and field are required.');
        }

        try {
            $content = Content::find($contentId);
            if (!$content) {
                return $this->handleError("No content #{$contentId} was found.");
            }

            // Persist through the content-data store (clears the content cache too).
            $content->setContentData([$field => $value]);
            $content->save();

            return "OK — set custom field \"{$field}\" = \"" . mb_substr($value, 0, 80)
                . "\" on content #{$contentId} (" . ($content->title ?: $content->content_type) . ").";
        } catch (\Throwable $e) {
            return $this->handleError('Could not set the custom field: ' . $e->getMessage());
        }
    }
}
