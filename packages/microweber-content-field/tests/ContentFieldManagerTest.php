<?php

namespace MicroweberPackages\ContentField\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\ContentField\ContentFieldManager;
use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\ContentField\Facades\ContentField;

class ContentFieldManagerTest extends TestCase
{
    use RefreshDatabase;

    private function manager(): ContentFieldManager
    {
        return ContentField::getFacadeRoot();
    }

    // ------------------------------------------------------------------
    //  Service provider
    // ------------------------------------------------------------------

    #[Test]
    public function it_resolves_from_container(): void
    {
        $this->assertInstanceOf(ContentFieldManager::class, $this->manager());
    }

    #[Test]
    public function it_is_a_singleton(): void
    {
        $this->assertSame($this->manager(), $this->manager());
    }

    // ------------------------------------------------------------------
    //  saveField
    // ------------------------------------------------------------------

    #[Test]
    public function it_saves_a_new_content_field(): void
    {
        $id = $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 1,
            'field'    => 'title',
            'value'    => 'Hello World',
        ]);

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        $this->assertDatabaseHas('content_fields', [
            'id'       => $id,
            'rel_type' => 'content',
            'rel_id'   => '1',
            'field'    => 'title',
            'value'    => 'Hello World',
        ]);
    }

    #[Test]
    public function it_updates_an_existing_content_field(): void
    {
        $id1 = $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 1,
            'field'    => 'title',
            'value'    => 'First',
        ]);

        $id2 = $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 1,
            'field'    => 'title',
            'value'    => 'Second',
        ]);

        $this->assertSame($id1, $id2);
        $this->assertDatabaseCount('content_fields', 1);
        $this->assertDatabaseHas('content_fields', [
            'id'    => $id1,
            'value' => 'Second',
        ]);
    }

    #[Test]
    public function it_returns_false_when_rel_type_is_missing(): void
    {
        $result = $this->manager()->saveField([
            'rel_id' => 1,
            'field'  => 'title',
            'value'  => 'x',
        ]);

        $this->assertFalse($result);
    }

    // ------------------------------------------------------------------
    //  getFieldData
    // ------------------------------------------------------------------

    #[Test]
    public function it_reads_field_value(): void
    {
        $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 5,
            'field'    => 'content_body',
            'value'    => '<p>Body</p>',
        ]);

        $value = $this->manager()->getFieldData('content_body', 'content', 5);
        $this->assertSame('<p>Body</p>', $value);
    }

    #[Test]
    public function it_reads_full_row(): void
    {
        $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 5,
            'field'    => 'content_body',
            'value'    => '<p>Body</p>',
        ]);

        $row = $this->manager()->getFieldData('content_body', 'content', 5, true);
        $this->assertIsArray($row);
        $this->assertSame('<p>Body</p>', $row['value']);
        $this->assertSame('content', $row['rel_type']);
    }

    #[Test]
    public function it_returns_false_for_missing_field(): void
    {
        $this->assertFalse($this->manager()->getFieldData('nonexistent', 'content', 1));
    }

    // ------------------------------------------------------------------
    //  getField (high-level)
    // ------------------------------------------------------------------

    #[Test]
    public function it_parses_query_string_input(): void
    {
        $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 10,
            'field'    => 'subtitle',
            'value'    => 'Sub',
        ]);

        $value = $this->manager()->getField('rel_type=content&field=subtitle&rel_id=10');
        $this->assertSame('Sub', $value);
    }

    #[Test]
    public function it_returns_all_rows_when_all_flag_set(): void
    {
        $this->manager()->saveField(['rel_type' => 'module', 'rel_id' => 0, 'field' => 'a', 'value' => '1']);
        $this->manager()->saveField(['rel_type' => 'module', 'rel_id' => 0, 'field' => 'b', 'value' => '2']);

        $rows = $this->manager()->getField([
            'rel_type' => 'module',
            'rel_id'   => 0,
            'all'      => 1,
        ]);

        $this->assertIsArray($rows);
        $this->assertCount(2, $rows);
    }

    // ------------------------------------------------------------------
    //  deleteField / deleteByRelation
    // ------------------------------------------------------------------

    #[Test]
    public function it_deletes_by_filter(): void
    {
        $this->manager()->saveField(['rel_type' => 'content', 'rel_id' => 1, 'field' => 'x', 'value' => 'v']);
        $this->assertDatabaseCount('content_fields', 1);

        $deleted = $this->manager()->deleteField(['rel_type' => 'content', 'rel_id' => 1]);
        $this->assertSame(1, $deleted);
        $this->assertDatabaseCount('content_fields', 0);
    }

    #[Test]
    public function it_deletes_by_relation(): void
    {
        $this->manager()->saveField(['rel_type' => 'page', 'rel_id' => 3, 'field' => 'a', 'value' => '1']);
        $this->manager()->saveField(['rel_type' => 'page', 'rel_id' => 3, 'field' => 'b', 'value' => '2']);
        $this->manager()->saveField(['rel_type' => 'page', 'rel_id' => 4, 'field' => 'c', 'value' => '3']);

        $deleted = $this->manager()->deleteByRelation('page', 3);
        $this->assertSame(2, $deleted);
        $this->assertDatabaseCount('content_fields', 1);
    }

    // ------------------------------------------------------------------
    //  fieldExists
    // ------------------------------------------------------------------

    #[Test]
    public function it_checks_field_existence(): void
    {
        $this->assertFalse($this->manager()->fieldExists('title', 'content', 1));

        $this->manager()->saveField(['rel_type' => 'content', 'rel_id' => 1, 'field' => 'title', 'value' => 'T']);

        $this->assertTrue($this->manager()->fieldExists('title', 'content', 1));
    }

    // ------------------------------------------------------------------
    //  Drafts
    // ------------------------------------------------------------------

    #[Test]
    public function it_saves_a_draft(): void
    {
        $id = $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 1,
            'field'    => 'content_body',
            'value'    => 'Draft value',
            'is_draft' => 1,
            'url'      => '/my-page',
        ]);

        $this->assertIsInt($id);
        $this->assertDatabaseHas('content_fields_drafts', [
            'id'       => $id,
            'rel_type' => 'content',
            'rel_id'   => '1',
            'field'    => 'content_body',
            'value'    => 'Draft value',
        ]);
        $this->assertDatabaseCount('content_fields', 0);
    }

    #[Test]
    public function it_reads_draft_via_getField(): void
    {
        $this->manager()->saveField([
            'rel_type' => 'content',
            'rel_id'   => 1,
            'field'    => 'title',
            'value'    => 'Draft Title',
            'is_draft' => 1,
            'url'      => '/test',
        ]);

        $rows = $this->manager()->getField([
            'rel_type' => 'content',
            'rel_id'   => 1,
            'field'    => 'title',
            'is_draft' => 1,
            'all'      => 1,
        ]);

        $this->assertIsArray($rows);
        $this->assertNotEmpty($rows);
        $this->assertSame('Draft Title', $rows[0]['value']);
    }

    // ------------------------------------------------------------------
    //  deduplicateGlobalFields
    // ------------------------------------------------------------------

    #[Test]
    public function it_deduplicates_global_fields(): void
    {
        // Insert duplicates directly
        DB::table('content_fields')->insert([
            ['rel_type' => 'module', 'rel_id' => '0', 'field' => 'header', 'value' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['rel_type' => 'module', 'rel_id' => '0', 'field' => 'header', 'value' => 'b', 'created_at' => now(), 'updated_at' => now()],
            ['rel_type' => 'module', 'rel_id' => '1', 'field' => 'header', 'value' => 'c', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->assertDatabaseCount('content_fields', 3);

        $this->manager()->deduplicateGlobalFields('header', 'module', '0');

        // After dedup, only the first row with rel_id=0 + the rel_id=1 row should remain
        // (the dedup removes rows where rel_id != target OR i > 1)
        $remaining = DB::table('content_fields')->count();
        $this->assertLessThanOrEqual(2, $remaining);
    }

    // ------------------------------------------------------------------
    //  Model classes
    // ------------------------------------------------------------------

    #[Test]
    public function content_field_model_uses_correct_table(): void
    {
        $model = new \MicroweberPackages\ContentField\ContentFieldModel();
        $this->assertSame('content_fields', $model->getTable());
    }

    #[Test]
    public function content_field_draft_model_uses_correct_table(): void
    {
        $model = new \MicroweberPackages\ContentField\ContentFieldDraftModel();
        $this->assertSame('content_fields_drafts', $model->getTable());
    }
}
