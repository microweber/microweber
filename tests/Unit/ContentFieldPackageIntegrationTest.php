<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\ContentField\ContentFieldManager;
use MicroweberPackages\ContentField\ContentFieldModel;
use MicroweberPackages\ContentField\ContentFieldDraftModel;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\ContentField\Facades\ContentField;
use MicroweberPackages\Url\Facades\UrlManager as MwUrl;

/**
 * Integration tests confirming the microweber-content-field package is
 * correctly wired into the CMS and edit fields work through the
 * content_field_manager service.
 */
class ContentFieldPackageIntegrationTest extends TestCase
{
    #[Test]
    public function content_field_manager_is_bound(): void
    {
        $this->assertTrue(app()->bound(\MicroweberPackages\ContentField\ContentFieldManager::class));
        $this->assertInstanceOf(ContentFieldManager::class, ContentField::getFacadeRoot());
    }

    #[Test]
    public function content_field_manager_is_singleton(): void
    {
        $this->assertSame(ContentField::getFacadeRoot(), ContentField::getFacadeRoot());
    }

    #[Test]
    public function save_and_read_via_content_field_manager(): void
    {
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $id = $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 999999,
            'field'    => 'test_field_pkg',
            'value'    => '<p>Package integration test</p>',
        ]);

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        $value = $cfm->getFieldData('test_field_pkg', 'content', 999999);
        $this->assertSame('<p>Package integration test</p>', $value);

        // Cleanup
        $cfm->deleteField(['id' => $id]);
    }

    #[Test]
    public function content_manager_edit_field_delegates_to_package(): void
    {
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 888888,
            'field'    => 'test_delegation',
            'value'    => 'delegation works',
        ]);

        // Call through the legacy content_manager path
        $value = app()->content_manager->edit_field([
            'rel_type' => 'content',
            'rel_id'   => 888888,
            'field'    => 'test_delegation',
        ]);

        $this->assertSame('delegation works', $value);

        // Cleanup
        $cfm->deleteByRelation('content', 888888);
    }

    #[Test]
    public function site_url_field_cast_expands_placeholder_on_read(): void
    {
        // The content_field package stores values verbatim (no url_manager dependency);
        // SiteUrlFieldCast — applied CMS-side by both edit_field and getEditFieldData —
        // expands the stored {SITE_URL} placeholder to the real site URL on read.
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 888890,
            'field'    => 'siteurl_field',
            'value'    => '<a href="{SITE_URL}about">about</a>',
        ]);

        $site = MwUrl::site_url();

        $data = \Modules\Content\Models\Content::getEditFieldData('siteurl_field', 'content', 888890);
        $this->assertIsArray($data);
        $this->assertStringContainsString($site . 'about', $data['value']);
        $this->assertStringNotContainsString('{SITE_URL}', $data['value']);

        $cfm->deleteByRelation('content', 888890);
    }

    #[Test]
    public function site_url_field_cast_is_a_no_op_for_non_arrays(): void
    {
        // Scalars / falsy / empty-array inputs pass through unchanged.
        $this->assertSame('plain', \Modules\Content\Support\SiteUrlFieldCast::expand('plain'));
        $this->assertFalse(\Modules\Content\Support\SiteUrlFieldCast::expand(false));
        $this->assertSame([], \Modules\Content\Support\SiteUrlFieldCast::expand([]));
    }

    #[Test]
    public function save_content_field_via_content_manager_uses_package(): void
    {
        $id = app()->content_manager->save_content_field([
            'rel_type' => 'content',
            'rel_id'   => 777777,
            'field'    => 'cm_integration',
            'value'    => 'saved via content_manager',
        ]);

        $this->assertIsInt($id);

        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();
        $value = $cfm->getFieldData('cm_integration', 'content', 777777);
        $this->assertSame('saved via content_manager', $value);

        // Cleanup
        $cfm->deleteByRelation('content', 777777);
    }

    #[Test]
    public function draft_save_and_read_via_package(): void
    {
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $id = $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 666666,
            'field'    => 'draft_test',
            'value'    => 'draft value',
            'is_draft' => 1,
            'url'      => '/test-draft-page',
        ]);

        $this->assertIsInt($id);
        $this->assertDatabaseHas('content_fields_drafts', [
            'id'    => $id,
            'value' => 'draft value',
        ]);

        // Should not appear in main table
        $this->assertDatabaseMissing('content_fields', [
            'field'  => 'draft_test',
            'rel_id' => '666666',
        ]);

        // Cleanup
        DB::table('content_fields_drafts')->where('id', $id)->delete();
    }

    #[Test]
    public function field_exists_check(): void
    {
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $this->assertFalse($cfm->fieldExists('nonexistent', 'content', 555555));

        $id = $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 555555,
            'field'    => 'exists_test',
            'value'    => 'yes',
        ]);

        $this->assertTrue($cfm->fieldExists('exists_test', 'content', 555555));

        // Cleanup
        $cfm->deleteField(['id' => $id]);
    }

    #[Test]
    public function models_use_correct_tables(): void
    {
        $this->assertSame('content_fields', (new ContentFieldModel())->getTable());
        $this->assertSame('content_fields_drafts', (new ContentFieldDraftModel())->getTable());
    }

    #[Test]
    public function update_existing_field_via_package(): void
    {
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $id1 = $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 444444,
            'field'    => 'update_test',
            'value'    => 'first',
        ]);

        $id2 = $cfm->saveField([
            'rel_type' => 'content',
            'rel_id'   => 444444,
            'field'    => 'update_test',
            'value'    => 'second',
        ]);

        $this->assertSame($id1, $id2, 'Should update the same row');
        $this->assertSame('second', $cfm->getFieldData('update_test', 'content', 444444));

        // Cleanup
        $cfm->deleteField(['id' => $id1]);
    }

    #[Test]
    public function delete_by_relation_removes_all(): void
    {
        /** @var ContentFieldManager $cfm */
        $cfm = ContentField::getFacadeRoot();

        $cfm->saveField(['rel_type' => 'test', 'rel_id' => 333333, 'field' => 'a', 'value' => '1']);
        $cfm->saveField(['rel_type' => 'test', 'rel_id' => 333333, 'field' => 'b', 'value' => '2']);
        $cfm->saveField(['rel_type' => 'test', 'rel_id' => 333334, 'field' => 'c', 'value' => '3']);

        $deleted = $cfm->deleteByRelation('test', 333333);
        $this->assertSame(2, $deleted);

        $this->assertFalse($cfm->fieldExists('a', 'test', 333333));
        $this->assertFalse($cfm->fieldExists('b', 'test', 333333));
        $this->assertTrue($cfm->fieldExists('c', 'test', 333334));

        // Cleanup
        $cfm->deleteByRelation('test', 333334);
    }
}
