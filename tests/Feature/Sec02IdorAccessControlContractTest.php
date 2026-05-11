<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * IDOR + role-based menu-hiding regression coverage.
 *
 * Pins:
 *   - `Modules\Content\Filament\Admin\ContentResource` declares
 *     `canAccess`, `canCreate`, `canEdit`, `canDelete`, and
 *     `canDeleteAny` server-side gates so non-admin users with
 *     admin-panel access cannot bypass menu-hiding via direct
 *     URL navigation to /admin/pages|posts|products/<id>/edit.
 *   - The gates derive the permission key from the resource's
 *     `$contentType` (e.g. `module.product.edit`,
 *     `module.post.edit`, `module.page.edit`) so subclasses
 *     (ProductResource / PostResource) inherit the right
 *     permission scope without needing to override.
 *   - The gate uses `user_can_access(...)` — the same helper
 *     that drives the menu-hiding CSS, so server-side and
 *     client-side state stay aligned.
 *
 * Contract test style: file-system reads only, no DB touch.
 */
class Sec02IdorAccessControlContractTest extends TestCase
{
    private const CONTENT_RESOURCE = 'Modules/Content/Filament/Admin/ContentResource.php';
    private const PRODUCT_RESOURCE = 'Modules/Product/Filament/Admin/Resources/ProductResource.php';
    private const POST_RESOURCE    = 'Modules/Post/Filament/Admin/Resources/PostResource.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function content_resource_declares_all_access_gates(): void
    {
        $src = $this->read(self::CONTENT_RESOURCE);

        $this->assertStringContainsString(
            'AI-133 / SEC-02 (cycle-115',
            $src,
            'ContentResource: must carry the IDOR-access-gates audit-trail marker'
        );

        // Each of the 5 access methods.
        foreach ([
            'public static function canAccess',
            'public static function canCreate',
            'public static function canEdit',
            'public static function canDelete',
            'public static function canDeleteAny',
        ] as $method) {
            $this->assertStringContainsString(
                $method,
                $src,
                "ContentResource: must declare `{$method}`"
            );
        }
    }

    #[Test]
    public function content_resource_gates_use_user_can_access_helper(): void
    {
        $src = $this->read(self::CONTENT_RESOURCE);

        // Each gate must call `user_can_access(...)` — the same gate
        // that drives the topbar Live-Edit chip so server-side and
        // client-side state stay aligned.
        $count = preg_match_all(
            '/user_can_access\\(\'module\\.\'\\s*\\.\\s*static::getContentTypeForAccess\\(\\)\\s*\\.\\s*\'\\.[a-z]+\'\\)/',
            $src
        );
        $this->assertGreaterThanOrEqual(
            5,
            $count,
            'ContentResource: each of the 5 can*() methods must call user_can_access(\'module.\' . static::getContentTypeForAccess() . \'.<verb>\')'
        );
    }

    #[Test]
    public function content_resource_derives_permission_key_from_content_type(): void
    {
        $src = $this->read(self::CONTENT_RESOURCE);

        // The base ContentResource has `$contentType` defined on its
        // subclasses; the helper reads it via property_exists check.
        $this->assertMatchesRegularExpression(
            '/protected static function getContentTypeForAccess\\(\\): string\\s*\\{[\\s\\S]{0,300}property_exists\\(static::class, \'contentType\'\\)/',
            $src,
            'ContentResource: getContentTypeForAccess() must check property_exists(static::class, \'contentType\')'
        );

        // Fallback to 'content' when subclass doesn't declare.
        $this->assertStringContainsString(
            "return 'content'",
            $src,
            'ContentResource: getContentTypeForAccess() must fall back to \'content\' for bare ContentResource'
        );
    }

    #[Test]
    public function product_resource_inherits_access_gates(): void
    {
        $src = $this->read(self::PRODUCT_RESOURCE);

        // ProductResource extends ContentResource — the gates are
        // inherited automatically.
        $this->assertStringContainsString(
            'class ProductResource extends ContentResource',
            $src,
            'ProductResource: must extend ContentResource (inherits the access gates)'
        );

        // And declares $contentType so getContentTypeForAccess()
        // resolves to `module.product.*`.
        $this->assertMatchesRegularExpression(
            '/protected static string \\$contentType\\s*=\\s*\'product\'/',
            $src,
            'ProductResource: must declare $contentType = \'product\' so the inherited gates resolve to module.product.*'
        );
    }

    #[Test]
    public function post_resource_inherits_access_gates(): void
    {
        $src = $this->read(self::POST_RESOURCE);

        $this->assertStringContainsString(
            'class PostResource extends ContentResource',
            $src,
            'PostResource: must extend ContentResource (inherits the access gates)'
        );
    }
}
