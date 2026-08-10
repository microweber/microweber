<?php

declare(strict_types=1);

namespace Modules\Ai\tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-mcpparent
 *
 * The MCP write tools CreateContentTool (content.create) and
 * CreateProductTool (product.create) persist new rows via
 * Content::create / Product::create. Microweber stores a NULL `parent`
 * when the column is omitted, but every admin page/product picker — the
 * /admin/pages + /admin/products listings, the Live Edit PageChip
 * (api/get_content_admin) and the page tree — filters root-level content
 * on `parent = 0`. So tool-created content with parent=NULL renders fine
 * on the public frontend yet is INVISIBLE in every admin picker.
 *
 * This was caught building a full demo site through the MCP server: the
 * 8 created pages did not appear in the Live Edit PageChip until parent
 * was set to 0. This contract pins that both create tools persist
 * parent=0 for root content so the regression cannot return.
 */
class McpCreateToolParentRootContractTest extends TestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function createToolSources(): array
    {
        return [
            'CreateContentTool' => ['Modules/Content/Tools/CreateContentTool.php'],
            'CreateProductTool' => ['Modules/Product/Tools/CreateProductTool.php'],
        ];
    }

    #[Test]
    #[DataProvider('createToolSources')]
    public function create_tool_persists_root_parent_zero(string $relativePath): void
    {
        $source = (string) file_get_contents(base_path($relativePath));

        // The data array assembled before Content::create / Product::create
        // must carry parent => 0 so root content is visible in admin pickers.
        $this->assertMatchesRegularExpression(
            '/[\'"]parent[\'"]\s*=>\s*0\b/',
            $source,
            $relativePath . ' must set parent => 0 on the created row — without it the row '
            . 'persists parent=NULL and is invisible in the admin Pages/Products pickers and the '
            . 'Live Edit PageChip (which filter root content on parent = 0).'
        );
    }
}
