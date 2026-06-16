<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\EditFieldExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Tests for EditFieldExtractor.
 */
class EditFieldExtractorTest extends TestCase
{
    private EditFieldExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extractor = new EditFieldExtractor();
    }

    // ── Finding edit fields ──

    public function test_find_content_edit_field(): void
    {
        $html = '<div class="edit" rel="content" field="content">Some content</div>';
        $fields = $this->extractor->findEditFields($html);

        $this->assertCount(1, $fields);
        $this->assertSame('content', $fields[0]['field']);
        $this->assertSame('content', $fields[0]['rel']);
        $this->assertNull($fields[0]['rel_id']);
    }

    public function test_find_global_edit_field(): void
    {
        $html = '<div class="edit" rel="global" field="header">Header</div>';
        $fields = $this->extractor->findEditFields($html);

        $this->assertCount(1, $fields);
        $this->assertSame('header', $fields[0]['field']);
        $this->assertSame('global', $fields[0]['rel']);
    }

    public function test_find_field_with_rel_id(): void
    {
        $html = '<div class="edit" rel="content" field="content_banner" rel-id="5">Banner</div>';
        $fields = $this->extractor->findEditFields($html);

        $this->assertCount(1, $fields);
        $this->assertSame('content_banner', $fields[0]['field']);
        $this->assertSame('5', $fields[0]['rel_id']);
    }

    public function test_find_multiple_edit_fields(): void
    {
        $html = '<div class="edit" rel="global" field="header">H</div>'
            . '<div class="edit" rel="content" field="content">C</div>'
            . '<div class="edit" rel="global" field="footer">F</div>';

        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(3, $fields);
    }

    public function test_no_edit_fields(): void
    {
        $html = '<div class="container"><p>No edit fields</p></div>';
        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(0, $fields);
    }

    public function test_edit_class_without_field_attr_ignored(): void
    {
        $html = '<div class="edit">No field attribute</div>';
        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(0, $fields);
    }

    public function test_has_edit_fields_true(): void
    {
        $html = '<div class="edit" rel="content" field="content">C</div>';
        $this->assertTrue($this->extractor->hasEditFields($html));
    }

    public function test_has_edit_fields_false(): void
    {
        $html = '<div>No edit fields</div>';
        $this->assertFalse($this->extractor->hasEditFields($html));
    }

    // ── Nested edit fields ──

    public function test_nested_edit_fields_both_found(): void
    {
        $html = '<div class="edit" rel="content" field="content">'
            . '<div class="edit" rel="content" field="banner" rel-id="1">'
            . '<module type="btn"/>'
            . '</div>'
            . '</div>';

        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(2, $fields);
        $this->assertSame('content', $fields[0]['field']);
        $this->assertSame('banner', $fields[1]['field']);
    }

    // ── data-field, data-rel variants ──

    public function test_data_field_attribute(): void
    {
        $html = '<div class="edit" data-rel="content" data-field="content">C</div>';
        $fields = $this->extractor->findEditFields($html);

        $this->assertCount(1, $fields);
        $this->assertSame('content', $fields[0]['field']);
        $this->assertSame('content', $fields[0]['rel']);
    }

    // ── Inherit scope ──

    public function test_inherit_edit_field(): void
    {
        $html = '<div class="edit" rel="inherit" field="sidebar" rel-id="10">S</div>';
        $fields = $this->extractor->findEditFields($html);

        $this->assertCount(1, $fields);
        $this->assertSame('sidebar', $fields[0]['field']);
        $this->assertSame('inherit', $fields[0]['rel']);
        $this->assertSame('10', $fields[0]['rel_id']);
    }

    // ── Content ID resolution ──

    public function test_resolve_content_id_for_content_rel(): void
    {
        $id = $this->extractor->resolveContentId('content', null, 3);
        $this->assertSame(3, $id);
    }

    public function test_resolve_content_id_with_explicit_rel_id(): void
    {
        $id = $this->extractor->resolveContentId('content', '5', 3);
        $this->assertSame(5, $id);
    }

    public function test_resolve_content_id_for_global(): void
    {
        $id = $this->extractor->resolveContentId('global', null, 3);
        $this->assertNull($id);
    }

    public function test_resolve_content_id_for_inherit(): void
    {
        $getParent = fn(int $id) => $id === 10 ? 3 : null;

        $id = $this->extractor->resolveContentId('inherit', '10', 10, $getParent);
        $this->assertSame(3, $id);
    }

    public function test_resolve_content_id_for_module(): void
    {
        $id = $this->extractor->resolveContentId('module', null, 3);
        $this->assertNull($id);
    }

    // ── Scope key ──

    public function test_scope_key_for_content(): void
    {
        $this->assertSame('3', $this->extractor->getScopeKey('content', 3));
    }

    public function test_scope_key_for_global(): void
    {
        $this->assertSame('global', $this->extractor->getScopeKey('global', null));
    }

    public function test_scope_key_for_module(): void
    {
        $this->assertSame('global', $this->extractor->getScopeKey('module', null));
    }

    // ── Field ranges (open + matching close) ──

    public function test_field_has_end_offset_past_close_tag(): void
    {
        $html = '<div class="edit" rel="content" field="content">BODY</div>TAIL';
        $fields = $this->extractor->findEditFields($html);

        $this->assertCount(1, $fields);
        $end = $fields[0]['end'];
        // 'end' is just past </div>; the trailing "TAIL" must be outside it.
        $this->assertSame('TAIL', substr($html, $end));
        $this->assertGreaterThan($fields[0]['offset'], $end);
    }

    public function test_nested_fields_have_correct_ranges(): void
    {
        $html = '<div class="edit" rel="content" field="outer">'
            . 'A<div class="edit" rel="content" field="inner" rel-id="1">B</div>C'
            . '</div>';

        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(2, $fields);

        [$outer, $inner] = $fields;
        // Outer fully encloses inner; inner's range is nested within outer's.
        $this->assertGreaterThan($outer['offset'], $inner['offset']);
        $this->assertLessThan($outer['end'], $inner['end']);
    }

    public function test_sibling_field_range_excludes_following_content(): void
    {
        $html = '<div class="edit" rel="content" field="a">X</div>'
            . '<div class="edit" rel="global" field="b">Y</div>';

        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(2, $fields);

        // The first field must close BEFORE the second field opens.
        $this->assertLessThanOrEqual($fields[1]['offset'], $fields[0]['end']);
    }

    public function test_div_nesting_does_not_close_field_early(): void
    {
        // A plain nested <div> must not be mistaken for the field's close.
        $html = '<div class="edit" rel="content" field="content">'
            . '<div class="inner"><span>x</span></div>'
            . '<module type="btn"/>'
            . '</div>AFTER';

        $fields = $this->extractor->findEditFields($html);
        $this->assertCount(1, $fields);
        // The module is inside the field; AFTER is outside.
        $modPos = strpos($html, '<module');
        $this->assertGreaterThan($fields[0]['offset'], $modPos);
        $this->assertLessThan($fields[0]['end'], $modPos);
        $this->assertSame('AFTER', substr($html, $fields[0]['end']));
    }
}
