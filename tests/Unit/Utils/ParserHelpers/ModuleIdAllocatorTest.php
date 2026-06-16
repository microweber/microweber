<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\ModuleIdAllocator;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ModuleIdAllocator class.
 *
 * Verifies that the ID generation matches the legacy rules documented in the user's examples.
 */
class ModuleIdAllocatorTest extends TestCase
{
    private ModuleIdAllocator $allocator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->allocator = new ModuleIdAllocator();
    }

    // ── Custom ID is preserved ──

    public function test_custom_id_is_not_touched(): void
    {
        $id = $this->allocator->allocate('btn', 'my-custom-btn-id-here', 'content', 'content', 3, '3');
        $this->assertSame('my-custom-btn-id-here', $id);
    }

    // ── Content scope: modules get content_id appended ──

    public function test_module_id_in_content_scope(): void
    {
        $id = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');
        $this->assertSame('module-layouts-3', $id);
    }

    public function test_duplicate_modules_in_content_scope(): void
    {
        $id1 = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');
        $id2 = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');
        $id3 = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');

        $this->assertSame('module-layouts-3', $id1);
        $this->assertSame('module-layouts-3--1', $id2);
        $this->assertSame('module-layouts-3--2', $id3);
    }

    // ── Global scope: no content_id ──

    public function test_module_id_in_global_scope(): void
    {
        $id = $this->allocator->allocate('layouts', null, 'global', 'header', null, 'global');
        $this->assertSame('module-layouts', $id);
    }

    public function test_duplicate_modules_in_global_scope(): void
    {
        $id1 = $this->allocator->allocate('layouts', null, 'global', 'header', null, 'global');
        $id2 = $this->allocator->allocate('layouts', null, 'global', 'header', null, 'global');

        $this->assertSame('module-layouts', $id1);
        $this->assertSame('module-layouts--1', $id2);
    }

    // ── Inherit scope: uses inherited parent's content_id ──

    public function test_module_id_in_inherit_scope(): void
    {
        // Content id = 10, but inherited parent = 3
        $id = $this->allocator->allocate('layouts', null, 'inherit', 'sidebar', 3, '3');
        $this->assertSame('module-layouts-3', $id);
    }

    // ── Complex mixed edit fields ──

    public function test_complex_mixed_edit_fields(): void
    {
        // Outer: rel=content, content_id=3
        $id1 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        $id2 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        $id3 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');

        $this->assertSame('module-btn-3', $id1);
        $this->assertSame('module-btn-3--1', $id2);
        $this->assertSame('module-btn-3--2', $id3);

        // Inner: rel=content, rel-id=1 (new scope)
        $id4 = $this->allocator->allocate('btn', null, 'content', 'content_banner', 1, '1');
        $id5 = $this->allocator->allocate('btn', null, 'content', 'content_banner', 1, '1');
        $id6 = $this->allocator->allocate('btn', null, 'content', 'content_banner', 1, '1');

        $this->assertSame('module-btn-1', $id4);
        $this->assertSame('module-btn-1--1', $id5);
        $this->assertSame('module-btn-1--2', $id6);

        // Custom ID in outer scope
        $id7 = $this->allocator->allocate('btn', 'my-custom-btn-id-here', 'content', 'content', 3, '3');
        $this->assertSame('my-custom-btn-id-here', $id7);
    }

    // ── Mixed global and content scopes on same page ──

    public function test_mixed_global_and_content_scopes(): void
    {
        // Global header
        $g1 = $this->allocator->allocate('layouts', null, 'global', 'header', null, 'global');
        $g2 = $this->allocator->allocate('layouts', null, 'global', 'header', null, 'global');

        $this->assertSame('module-layouts', $g1);
        $this->assertSame('module-layouts--1', $g2);

        // Content section (separate scope)
        $c1 = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');
        $c2 = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');

        $this->assertSame('module-layouts-3', $c1);
        $this->assertSame('module-layouts-3--1', $c2);

        // Global footer (continues global scope counter)
        $g3 = $this->allocator->allocate('layouts', null, 'global', 'footer', null, 'global');
        $g4 = $this->allocator->allocate('layouts', null, 'global', 'footer', null, 'global');

        $this->assertSame('module-layouts--2', $g3);
        $this->assertSame('module-layouts--3', $g4);
    }

    // ── ID cleaning ──

    public function test_module_id_cleaning(): void
    {
        $this->assertSame('module-layouts', $this->allocator->cleanModId('module-layouts'));
        $this->assertSame('module-text-multiple-columns', $this->allocator->cleanModId('module-text/multiple_columns'));
        $this->assertSame('module-my-mod', $this->allocator->cleanModId('module-my mod'));
    }

    // ── CSS class generation ──

    public function test_module_css_class(): void
    {
        $this->assertSame('module-btn', $this->allocator->moduleCssClass('btn'));
        $this->assertSame('module-layouts', $this->allocator->moduleCssClass('layouts'));
        $this->assertSame('module-text-multiple-columns', $this->allocator->moduleCssClass('text/multiple_columns'));
    }

    // ── Many identical modules ──

    public function test_many_identical_modules_unique_ids(): void
    {
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $id = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
            $this->assertNotContains($id, $ids, "Module ID collision at iteration $i");
            $ids[] = $id;
        }
        $this->assertCount(10, array_unique($ids));
    }

    // ── Database ID collision avoidance ──

    public function test_database_id_collision_avoidance(): void
    {
        $this->allocator->registerDatabaseId('module-btn-3');

        $id = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        // Should skip module-btn-3 since it's in the database
        $this->assertSame('module-btn-3--1', $id);
    }

    // ── Different module types in same scope ──

    public function test_different_types_independent_counters(): void
    {
        $btn1 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        $layout1 = $this->allocator->allocate('layouts', null, 'content', 'content', 3, '3');
        $btn2 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');

        $this->assertSame('module-btn-3', $btn1);
        $this->assertSame('module-layouts-3', $layout1);
        $this->assertSame('module-btn-3--1', $btn2);
    }

    // ── Reset ──

    public function test_reset(): void
    {
        $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        $this->allocator->reset();

        $id = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        $this->assertSame('module-btn-3', $id, 'After reset, counters should restart');
    }

    // ── Page scope (same as content) ──

    public function test_page_scope(): void
    {
        $id = $this->allocator->allocate('btn', null, 'page', 'content', 5, '5');
        $this->assertSame('module-btn-5', $id);
    }

    // ── Post scope ──

    public function test_post_scope(): void
    {
        $id = $this->allocator->allocate('btn', null, 'post', 'content', 7, '7');
        $this->assertSame('module-btn-7', $id);
    }

    // ── Module scope (parent module field prefix) ──

    public function test_module_scope_with_parent_field(): void
    {
        $id = $this->allocator->allocate('btn', null, 'module', 'sidebar', null, 'parent-id', 'sidebar');
        $this->assertSame('sidebar-module-btn', $id);
    }

    // ── No edit field (outside .edit) ──

    public function test_no_edit_field(): void
    {
        $id = $this->allocator->allocate('btn', null, '', null, null, 'global');
        $this->assertSame('module-btn', $id);
    }

    // ── More edge cases ──

    public function test_custom_id_with_special_chars_preserved_verbatim(): void
    {
        // An explicit id is returned untouched (not cleaned/lowercased).
        $id = $this->allocator->allocate('btn', 'My_Custom/ID-Here', 'content', 'content', 3, '3');
        $this->assertSame('My_Custom/ID-Here', $id);
    }

    public function test_two_scopes_do_not_share_counters(): void
    {
        $a1 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        $b1 = $this->allocator->allocate('btn', null, 'content', 'banner', 5, '5');
        $a2 = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');

        $this->assertSame('module-btn-3', $a1);
        $this->assertSame('module-btn-5', $b1);
        $this->assertSame('module-btn-3--1', $a2);
    }

    public function test_slash_type_id_is_dashed(): void
    {
        $id = $this->allocator->allocate('shop/products', null, 'content', 'content', 3, '3');
        $this->assertSame('module-shop-products-3', $id);
    }

    public function test_clean_mod_id_normalises_separators(): void
    {
        $this->assertSame('a-b-c-d-e', $this->allocator->cleanModId('a/b\\c d.e'));
        $this->assertSame('x-y', $this->allocator->cleanModId('X;Y'));
    }

    public function test_database_id_collision_pushes_counter(): void
    {
        $this->allocator->registerDatabaseId('module-btn-3');
        $this->allocator->registerDatabaseId('module-btn-3--1');

        $id = $this->allocator->allocate('btn', null, 'content', 'content', 3, '3');
        // Both module-btn-3 and module-btn-3--1 are taken by the DB.
        $this->assertSame('module-btn-3--2', $id);
    }

    public function test_eight_siblings_all_unique(): void
    {
        $ids = [];
        for ($i = 0; $i < 8; $i++) {
            $ids[] = $this->allocator->allocate('layouts', null, 'content', 'content', 1, '1');
        }
        $this->assertCount(8, array_unique($ids));
        $this->assertSame('module-layouts-1', $ids[0]);
        $this->assertSame('module-layouts-1--7', $ids[7]);
    }
}
