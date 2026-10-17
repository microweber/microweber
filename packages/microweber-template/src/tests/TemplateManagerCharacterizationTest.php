<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Characterization tests pinning the CURRENT behaviour of TemplateManager's
 * read-only helper bands (name/folder/fields/ids). These lock behaviour in
 * place so the 1043-line god class can be decomposed into Concerns traits
 * (whole-method moves) without silent regressions. Read-only — no mutation.
 */
class TemplateManagerCharacterizationTest extends TestCase
{
    private function tm()
    {
        return app()->template_manager;
    }

    #[Test]
    public function name_mirrors_folder_name_and_is_non_empty(): void
    {
        // Active template varies across the suite (other tests switch it), so
        // pin the invariant: name() returns the same non-empty folder as folder_name().
        $folder = $this->tm()->folder_name();
        $this->assertNotSame('', $folder);
        $this->assertSame($folder, $this->tm()->name());
    }

    #[Test]
    public function is_laravel_template_returns_a_bool(): void
    {
        $this->assertIsBool($this->tm()->is_laravel_template());
    }

    #[Test]
    public function data_and_edit_field_titles_default_to_empty_string(): void
    {
        $this->assertSame('', $this->tm()->get_data_field_title('title'));
        $this->assertSame('', $this->tm()->get_edit_field_title('title'));
    }

    #[Test]
    public function data_and_edit_fields_return_arrays(): void
    {
        $this->assertIsArray($this->tm()->get_data_fields('general'));
        $this->assertIsArray($this->tm()->get_edit_fields('general'));
    }

    #[Test]
    public function content_context_ids_default_to_zero_without_a_page(): void
    {
        $this->assertSame(0, $this->tm()->getContentId());
        $this->assertSame(0, $this->tm()->getPageId());
        $this->assertSame(0, $this->tm()->getPostId());
        $this->assertSame(0, $this->tm()->getProductId());
        $this->assertSame(0, $this->tm()->getCategoryId());
    }
}
