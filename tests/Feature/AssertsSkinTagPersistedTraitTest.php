<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AssertsSkinTagPersisted;
use Tests\TestCase;

/**
 * Plan B.3 second-bullet contract — pin the
 * {@see AssertsSkinTagPersisted} trait's behaviour against a real
 * DB so the per-skin Dusk tests inherit a verified gate, not a
 * "looks right" one.
 *
 * Three contract slices:
 *
 *   1. The skin tag is found when it lives in `content.content`
 *      (the most common landing column for Plan B.2's per-skin
 *      Dusk test fixtures).
 *   2. The skin tag is found when it lives in `content.content_body`
 *      (some skins write here instead — ecommerce/blog notably).
 *   3. The skin tag is NOT found when nothing was persisted, AND
 *      the failure message names both the page id and the skin
 *      tag (so the operator can act on it).
 *
 * Lives under `tests/Feature/` because the trait reads `content`
 * and `content_fields` rows via the Laravel DB facade — the
 * application container has to be booted. Inserts a single
 * marker-prefixed row and tears it down in setUp / tearDown so
 * the test leaves no residue.
 */
class AssertsSkinTagPersistedTraitTest extends TestCase
{
    use AssertsSkinTagPersisted;

    private const FIXTURE_MARKER = 'asserts-skin-tag-persisted-trait-test';

    protected function setUp(): void
    {
        parent::setUp();
        $this->purgeFixture();
    }

    protected function tearDown(): void
    {
        $this->purgeFixture();
        parent::tearDown();
    }

    private function purgeFixture(): void
    {
        DB::table('content')
            ->where('url', 'like', self::FIXTURE_MARKER . '%')
            ->delete();
    }

    private function seedPage(string $contentColumn, string $value): int
    {
        return (int) DB::table('content')->insertGetId([
            'title' => 'Skin tag trait fixture',
            'content_type' => 'page',
            'subtype' => 'static',
            'url' => self::FIXTURE_MARKER . '-' . uniqid(),
            'is_active' => 1,
            $contentColumn => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[Test]
    public function skin_tag_is_found_when_persisted_in_content_column(): void
    {
        $pageId = $this->seedPage(
            'content',
            '<module type="layouts" template="pricing/skin-1" id="x"/>',
        );

        $this->assertSkinTagPersisted($pageId, 'pricing/skin-1');
    }

    #[Test]
    public function skin_tag_is_found_when_persisted_in_content_body_column(): void
    {
        $pageId = $this->seedPage(
            'content_body',
            '<div><module type="layouts" template="ecommerce/skin-1" id="y"/></div>',
        );

        $this->assertSkinTagPersisted($pageId, 'ecommerce/skin-1');
    }

    #[Test]
    public function missing_skin_tag_fails_with_a_page_id_and_skin_naming_message(): void
    {
        $pageId = $this->seedPage(
            'content',
            '<module type="layouts" template="features/skin-2" id="z"/>',
        );

        try {
            $this->assertSkinTagPersisted($pageId, 'features/skin-9999-missing');
            $this->fail('Expected AssertsSkinTagPersisted to throw on a missing skin tag');
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString(
                'features/skin-9999-missing',
                $e->getMessage(),
                'Failure message must name the missing skin tag'
            );
            $this->assertStringContainsString(
                (string) $pageId,
                $e->getMessage(),
                'Failure message must name the page id so the operator can pull the row'
            );
        }
    }
}
