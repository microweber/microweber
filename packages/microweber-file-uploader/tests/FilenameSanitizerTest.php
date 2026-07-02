<?php

namespace MicroweberPackages\FileUploader\Tests;

use MicroweberPackages\FileUploader\Support\FilenameSanitizer;

class FilenameSanitizerTest extends TestCase
{
    protected FilenameSanitizer $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sanitizer = new FilenameSanitizer();
    }

    public function test_sanitize_preserves_extension(): void
    {
        $result = $this->sanitizer->sanitize('test.jpg');
        $this->assertStringEndsWith('.jpg', $result);
    }

    public function test_sanitize_lowercases_filename(): void
    {
        $result = $this->sanitizer->sanitize('MyFile.JPG');
        $this->assertEquals(strtolower($result), $result);
        $this->assertStringEndsWith('.jpg', $result);
    }

    public function test_sanitize_removes_special_characters(): void
    {
        $result = $this->sanitizer->sanitize('file (1) #test!.jpg');
        $this->assertDoesNotMatchRegularExpression('/[()#!]/', $result);
        $this->assertStringEndsWith('.jpg', $result);
    }

    public function test_sanitize_replaces_spaces(): void
    {
        $result = $this->sanitizer->sanitize('my file name.jpg');
        $this->assertStringNotContainsString(' ', $result);
        $this->assertStringEndsWith('.jpg', $result);
    }

    public function test_sanitize_handles_double_dots(): void
    {
        $result = $this->sanitizer->sanitize('file..name.jpg');
        $this->assertStringNotContainsString('..', $result);
    }

    public function test_sanitize_handles_empty_name(): void
    {
        $result = $this->sanitizer->sanitize('.jpg');
        $this->assertStringEndsWith('.jpg', $result);
        $this->assertNotEquals('.jpg', $result); // should have at least "file.jpg"
    }

    public function test_make_unique_adds_timestamp(): void
    {
        $result = $this->sanitizer->makeUnique('test.jpg');
        $this->assertStringEndsWith('.jpg', $result);
        $this->assertNotEquals('test.jpg', $result);
        $this->assertGreaterThan(strlen('test.jpg'), strlen($result));
    }

    public function test_make_unique_preserves_extension(): void
    {
        $result = $this->sanitizer->makeUnique('document.pdf');
        $this->assertStringEndsWith('.pdf', $result);
    }

    public function test_sanitize_removes_path_traversal(): void
    {
        $result = $this->sanitizer->sanitize('../../../etc/passwd.jpg');
        $this->assertStringNotContainsString('..', $result);
        $this->assertStringNotContainsString('/', $result);
    }

    public function test_sanitize_removes_ampersand_percent(): void
    {
        $result = $this->sanitizer->sanitize('file&name%test.jpg');
        $this->assertStringNotContainsString('&', $result);
        $this->assertStringNotContainsString('%', $result);
    }
}